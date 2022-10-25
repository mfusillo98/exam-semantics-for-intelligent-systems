<?php

namespace App\Controllers\Website;


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
                ->from(CoursesModel::class)
                ->SQLWhere("title LIKE '%$queryParams[query]%'"),
            ["course_id"],
            10,
            'DESC'
        );

        return new FuxResponse(FuxResponse::SUCCESS, null, $pagination->get(($queryParams['cursor'] ?? null) ?: null));
    }
}
