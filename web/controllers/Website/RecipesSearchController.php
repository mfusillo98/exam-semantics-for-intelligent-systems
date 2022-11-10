<?php

namespace App\Controllers\Website;


use App\Models\IngredientsModel;
use App\Models\IngredientsRecipesModel;
use App\Models\IngredientsUserModel;
use App\Models\RecipesModel;
use Fux\Database\Pagination\Cursor\Pagination;
use Fux\FuxQueryBuilder;
use Fux\FuxResponse;
use Fux\Request;

class RecipesSearchController
{

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
         * ]
         */

        $queryParams = $request->getQueryStringParams();

        $ingredients = explode(";", $queryParams['query']);

        $recipeScoreQb = (new FuxQueryBuilder())
            ->select("r.recipe_id, r.title, GROUP_CONCAT(DISTINCT i.name, ' | ') as ingredients_list", "SUM(i.carbon_foot_print_z_score + i.water_foot_print_z_score) as static_score")
            ->from(RecipesModel::class, "r")
            ->leftJoin(IngredientsRecipesModel::class, "ir.recipe_id = r.recipe_id", "ir")
            ->leftJoin(IngredientsModel::class, "ir.ingredient_id = i.ingredient_id", "i")
            ->whereGreaterEqThan("r.trust_cfp", 1)
            ->whereGreaterEqThan("r.trust_wfp", 1)
            ->groupBy("r.recipe_id");


        if (($queryParams['useCarbonFreeIngredients'] ?? 0) == 1) {
            $user_id = null; //TODO check for logged user
            $carbonFreeIngredientIds = IngredientsUserModel::listWhere(["type" => "km0", "user_id" => $user_id])->column('ingredient_id');

            if ($carbonFreeIngredientIds) {
                //Modifico la select della query
                $recipeScoreQb->select("r.recipe_id, r.title, GROUP_CONCAT(DISTINCT i.name, ' | ') as ingredients_list", "SUM(IFNULL(cfi.carbon_foot_print_z_score, i.carbon_foot_print_z_score) + i.water_foot_print_z_score) as static_score");

                $tmpTable = [];
                foreach ($carbonFreeIngredientIds as $ingredient_id) {
                    $tmpTable[] = "SELECT $ingredient_id as ingredient_id, 0-food_print.get_global_mean_cfp()/food_print.get_global_std_dev_cfp() as carbon_foot_print_z_score"; //simulating 0-emission cfp value
                }

                $recipeScoreQb->leftJoin("(" . implode(' UNION ALL ', $tmpTable) . ")", "i.ingredient_id = cfi.ingredient_id", "cfi"); //cfi = carbon free ingredients
            }
        }

        $qb = (new FuxQueryBuilder())->select("*")->from($recipeScoreQb, "recipes");

        foreach ($ingredients as $ingredient) {
            $qb->whereLike("ingredients_list", "%$ingredient%");
        }
        $qb->orderBy("static_score", "ASC");

        $pagination = new Pagination(
            $qb,
            ["recipe_id"],
            10,
            'DESC'
        );

        return new FuxResponse(FuxResponse::SUCCESS, null, $pagination->get(($queryParams['cursor'] ?? null) ?: null));
    }
}
