<?php

namespace App\Controllers\Website;


use App\Models\IngredientsModel;
use App\Models\IngredientsRecipesModel;
use App\Models\IngredientsUserModel;
use App\Models\RecipesModel;
use App\Models\UsersModel;
use App\Packages\Auth\Auth;
use App\Utils\RecipeUtils;
use App\Models\IngredientsNameAliasModel;
use Fux\Database\Pagination\Cursor\Pagination;
use Fux\FuxQueryBuilder;
use Fux\FuxResponse;
use Fux\Request;

class RecipesSearchController
{

    const TRUST_CFP_MIN = 0.8;
    const TRUST_WFP_MIN = 0.8;
    const ONLY_ENABLED_RECIPES = false;
    const ONLY_RATED_RECIPES = true;

    /**
     * Effettua una ricerca all'interno dei corsi
     * @param Request $request
     * @return FuxResponse
     */
    public static function doSearch(Request $request)
    {
        /**
         * @var array $queryParams = [
         *     "query" => "history",
         *     "cursor" => "asd123",
         *     "useCfi" => 1 | 0, //optional, whether to use user carbon free ingredients
         *     "sustainabilityWeight" => 0.5, //a value in range [0,1]
         * ]
         */

        $queryParams = $request->getQueryStringParams();

        $ingredients = explode(";", $queryParams['query']);

        //We need to apply a min-max normalization on recipes sustainability score. This is needed because we have to weight both sustainability score and rating score
        $sustainabilityRange = RecipeUtils::getMinMaxSustainabilityScore(
            self::ONLY_ENABLED_RECIPES ? 0 : null,
            self::TRUST_CFP_MIN,
            self::TRUST_WFP_MIN,
            false //We consider all recipes also those that were not rated on the website
        );
        $sustainabilityRangeSize = $sustainabilityRange['max'] - $sustainabilityRange['min'];

        $ratingCountRange = RecipeUtils::getMinMaxRatingCountScore(
            self::ONLY_ENABLED_RECIPES ? 0 : null,
            self::TRUST_CFP_MIN,
            self::TRUST_WFP_MIN,
            false //We consider all recipes also those that were not rated on the website
        );
        $ratingCountRangeSize = $ratingCountRange['max'] - $ratingCountRange['min'];

        $sustainabilityWeight = min(1, ($queryParams['sustainabilityWeight'] ?? 100) / 100);
        $ratingWeight = 1 - $sustainabilityWeight;

        //Build a json with list of ingredients and their carbon foot print
        $ingredientsSelect =
            "CONCAT('
                {\"ingredients_list\":[', 
                GROUP_CONCAT(DISTINCT CONCAT('{\"name\":\"', i.name, '\", \"carbon_foot_print\":\"', i.carbon_foot_print, '\"}') SEPARATOR ','), 
                ']}') as ingredients_list";

        $sustainabilityScoreSQL = "(static_score)";
        $ratingScoreSQL = "((IFNULL(r.rating_count, 1) - $ratingCountRange[min])/$ratingCountRangeSize)";

        $recipeScoreQb = (new FuxQueryBuilder())
            ->select(
                "r.recipe_id", "r.title", "r.rating", "r.rating_count" , "GROUP_CONCAT(DISTINCT lower(ina.name)) as ingredients_list", "r.url",
                "$sustainabilityScoreSQL as sustainability_score",
                "$sustainabilityWeight * $sustainabilityScoreSQL + $ratingWeight * (1 - (r.rating/5) * $ratingScoreSQL) as weighted_score")
            ->from(RecipesModel::class, "r")
            ->leftJoin(IngredientsRecipesModel::class, "ir.recipe_id = r.recipe_id", "ir")
            ->leftJoin(IngredientsModel::class, "ir.ingredient_id = i.ingredient_id", "i")
			->leftJoin(IngredientsNameAliasModel::class, "ir.ingredient_id = ina.ingredient_id", "ina")
            ->groupBy("r.recipe_id");

        if (self::TRUST_CFP_MIN) $recipeScoreQb->whereGreaterEqThan("r.trust_cfp", self::TRUST_CFP_MIN);
        if (self::TRUST_WFP_MIN) $recipeScoreQb->whereGreaterEqThan("r.trust_wfp", self::TRUST_WFP_MIN);
        if (self::ONLY_ENABLED_RECIPES) $recipeScoreQb->where("r.disabled", 0);
        if (self::ONLY_RATED_RECIPES) $recipeScoreQb->whereNotNull("r.rating");


        //In order to use carbon free ingredients data of the logged user we need to edit the select statement of the query
        //And we need to join our recipes with a dynamically computed cfp z-score for the given ingredients
        if (($queryParams['useCarbonFreeIngredients'] ?? 0) == 1) {
            $user_id = Auth::user(UsersModel::class)["user_id"];
            $carbonFreeIngredientIds = IngredientsUserModel::listWhere(["type" => "km0", "user_id" => $user_id])->column('ingredient_id');

            if ($carbonFreeIngredientIds) {
                //Modifico la select della query
                $sustainabilityScoreSQL = "((SUM(IFNULL(cfi.carbon_foot_print_z_score, i.carbon_foot_print_z_score) + i.water_foot_print_z_score) - $sustainabilityRange[min]) / $sustainabilityRangeSize)";
                $recipeScoreQb->select("r.recipe_id", "r.title", "GROUP_CONCAT(DISTINCT i.name, ' | ') as ingredients_list", "r.url",
                    "$sustainabilityScoreSQL as sustainability_score",
                    "$sustainabilityWeight * $sustainabilityScoreSQL + $ratingWeight * (1 - (r.rating/5) * $ratingScoreSQL) as weighted_score"
                );

                $tmpTable = [];
                foreach ($carbonFreeIngredientIds as $ingredient_id) {
                    $tmpTable[] = "SELECT $ingredient_id as ingredient_id, 0-food_print.get_global_mean_cfp()/food_print.get_global_std_dev_cfp() as carbon_foot_print_z_score"; //simulating 0-emission cfp value
                }

                $recipeScoreQb->leftJoin("(" . implode(' UNION ALL ', $tmpTable) . ")", "i.ingredient_id = cfi.ingredient_id", "cfi"); //cfi = carbon free ingredients
            }
        }

        //Assigning a "row num" to the filtered recipes sorted by static score. This is needed in order to use a cursor pagination.
        $rankedRecipes = (new FuxQueryBuilder())->select("*", '@rownum := @rownum + 1 AS rank_')->from($recipeScoreQb, "recipes, (SELECT @rownum := 0) ranking");

        foreach ($ingredients as $ingredient) {
            $rankedRecipes->whereLike("ingredients_list", "%$ingredient%");
        }
        $rankedRecipes->orderBy("weighted_score", "ASC");

        $qb = (new FuxQueryBuilder())->select("*")->from($rankedRecipes, "ranked_recipes");

        $pagination = new Pagination(
            $qb,
            ["rank_"],
            10,
            'ASC'
        );

        $page = $pagination->get(($queryParams['cursor'] ?? null) ?: null);

        $recipes = $page->getItems();
        foreach ($recipes as &$r){
            $r["ingredients_list"] = (new FuxQueryBuilder())
                ->select("i.ingredient_id", "lower(ina.name) as name", "i.carbon_foot_print")
                ->from(IngredientsRecipesModel::class,"ir")
                ->leftJoin(IngredientsModel::class, "ir.ingredient_id=i.ingredient_id", "i")
				->leftJoin(IngredientsNameAliasModel::class, "ir.ingredient_id = ina.ingredient_id", "ina")
                ->where("ir.recipe_id", $r["recipe_id"])
                ->execute();
        }
        $page->setItems($recipes);

        return new FuxResponse(FuxResponse::SUCCESS, null, $page);
    }
}
