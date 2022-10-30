<?php

namespace App\Controllers\Website;


use App\Models\IngredientsModel;
use App\Models\IngredientsRecipesModel;
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
         *      "cursor" => "asd123"
         * ]
         */

        $queryParams = $request->getQueryStringParams();

        $ingredients = explode(";", $queryParams['query']);

        $qb = (new FuxQueryBuilder())
            ->select("*")
            ->from(
                (new FuxQueryBuilder())
                    ->select("r.recipe_id, r.title, GROUP_CONCAT(DISTINCT i.name, ' | ') as ingredients_list", "r.static_score")
                    ->from(RecipesModel::class, "r")
                    ->leftJoin(IngredientsRecipesModel::class, "ir.recipe_id = r.recipe_id", "ir")
                    ->leftJoin(IngredientsModel::class, "ir.ingredient_id = i.ingredient_id", "i")
                    ->whereGreaterEqThan("r.trust_cfp", 1)
                    ->whereGreaterEqThan("r.trust_wfp", 1)
                    ->groupBy("r.recipe_id")
                , "recipes");

        foreach ($ingredients as $ingredient) {
            $qb->whereLike("ingredients_list", "%$ingredient%");
        }
        $qb->orderBy("static_score","ASC");

        $pagination = new Pagination(
            $qb,
            ["recipe_id"],
            10,
            'DESC'
        );

        return new FuxResponse(FuxResponse::SUCCESS, null, $pagination->get(($queryParams['cursor'] ?? null) ?: null));
    }
}
