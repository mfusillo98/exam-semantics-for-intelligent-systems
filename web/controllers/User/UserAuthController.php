<?php

namespace App\Controllers\User;

use App\Models\IngredientsModel;
use Fux\FuxResponse;
use Fux\Request;

class UserAuthController {

    public static function getIngredients(Request $request){
        $query = $request->getQueryStringParams()["query"];
        return new FuxResponse(FuxResponse::SUCCESS, "Ingredients taken", ["ingredients" => IngredientsModel::listWhere("name like '%$query%'")]);
    }

    public static function signUp(Request $request){
        $body = $request->getBody();
        echo json_encode($body);
    }

}