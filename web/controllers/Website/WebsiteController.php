<?php

namespace App\Controllers\Website;

use App\Models\IngredientsModel;
use App\Models\RecipesModel;

class WebsiteController{

    public static function index(){
        $totIngredients = IngredientsModel::getAggregateWhere("COUNT", "ingredient_id");
        $totRecipes = RecipesModel::getAggregateWhere("COUNT", "recipe_id");
        return view("website/index", ["totIngredients" => $totIngredients, "totRecipes" => $totRecipes]);
    }

    public static function about(){
        return view("website/about");
    }

    public static function contact(){
        return view("website/contact");
    }

    public static function user(){
        return view("website/user");
    }

}
