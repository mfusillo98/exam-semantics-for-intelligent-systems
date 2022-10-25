<?php

namespace App\Controllers\Website;


use App\Models\IngredientsModel;
use App\Models\IngredientsRecipesModel;
use App\Models\RecipesModel;
use Fux\Database\Pagination\Cursor\Pagination;
use Fux\FuxQueryBuilder;
use Fux\FuxResponse;
use Fux\Request;

class RecipesSearchController {

    /**
     * Effettua una ricerca all'interno dei corsi
     * @param Request $request
     * @return FuxResponse
     */
    public static function doSearch(Request $request){
        /**
         * @var array $queryParams = [
         *     "query" => "history",
         *      "cursor" => "asd123"
         * ]
         */

        $queryParams = $request->getQueryStringParams();

        $pagination = new Pagination(
            (new FuxQueryBuilder())
                ->select("*")
                ->from((new FuxQueryBuilder())
                    ->select("r.recipe_id, r.title, GROUP_CONCAT(DISTINCT i.name, ', ') as ingredients_list")
                    ->from(RecipesModel::class, "r")
                    ->leftJoin(IngredientsRecipesModel::class, "ir.recipe_id = r.recipe_id", "ir")
                    ->leftJoin(IngredientsModel::class, "ir.ingredient_id = i.ingredient_id", "i")
                    ->groupBy("r.recipe_id"), "recipes")
                ->SQLWhere("ingredients_list LIKE '%$queryParams[query]%'"),
            ["recipe_id"],
            10,
            'DESC'
        );

        return new FuxResponse(FuxResponse::SUCCESS, null, $pagination->get(($queryParams['cursor'] ?? null) ?: null));
    }
}
