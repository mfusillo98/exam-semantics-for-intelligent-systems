<?php

namespace App\Controllers\SurveyUsers;

use App\Models\IngredientsModel;
use App\Models\IngredientsUserModel;
use App\Models\SurveyUsersModel;
use App\Models\SurveyUsersRecipesModel;
use App\Models\UsersModel;
use App\Packages\Auth\Auth;
use App\Recipes\RecipesConstants;
use App\Recipes\RecipesUtils;
use Fux\DB;
use Fux\FuxResponse;
use Fux\Request;

class SurveyUsersController {

    /**
     * Returns survey page for users
     * @return string
     */
    public static function index()
    {
        $recipes = [];

        //Get a random element for best recipes
        $recipes["best_recipes"]["firsts"] = RecipesUtils::getRecipeInformation(RecipesConstants::BEST_RECIPES["firsts"][array_rand(RecipesConstants::BEST_RECIPES["firsts"])]);
        $recipes["best_recipes"]["seconds_meat"] = RecipesUtils::getRecipeInformation(RecipesConstants::BEST_RECIPES["seconds_meat"][array_rand(RecipesConstants::BEST_RECIPES["seconds_meat"])]);
        $recipes["best_recipes"]["desserts"] = RecipesUtils::getRecipeInformation(RecipesConstants::BEST_RECIPES["desserts"][array_rand(RecipesConstants::BEST_RECIPES["desserts"])]);

        //Get a random element for worst recipes
        $recipes["worst_recipes"]["firsts"] = RecipesUtils::getRecipeInformation(RecipesConstants::WORST_RECIPES["firsts"][array_rand(RecipesConstants::WORST_RECIPES["firsts"])]);
        $recipes["worst_recipes"]["seconds_meat"] = RecipesUtils::getRecipeInformation(RecipesConstants::WORST_RECIPES["seconds_meat"][array_rand(RecipesConstants::WORST_RECIPES["seconds_meat"])]);
        $recipes["worst_recipes"]["desserts"] = RecipesUtils::getRecipeInformation(RecipesConstants::WORST_RECIPES["desserts"][array_rand(RecipesConstants::WORST_RECIPES["desserts"])]);


        return view('surveyUsers/index', ["recipes" => $recipes]);
    }


    public static function save(Request $request){
        $body = $request->getBody();

        DB::ref()->begin_transaction();
        if(!$user_id = SurveyUsersModel::save($body)){
            DB::ref()->rollback();
            return new FuxResponse(FuxResponse::ERROR, "We cannot save your information, try later");
        }

        foreach (["firsts", "seconds_meat", "desserts"] as $type){
            $recipes = explode("_", $body[$type]);
            if(!SurveyUsersRecipesModel::save([
                "survey_user_id" => $user_id,
                "type" => $type,
                "chosen_recipe_id" => $recipes[0],
                "other_recipe_id" => $recipes[1]
            ])){
                DB::ref()->rollback();
                return new FuxResponse(FuxResponse::ERROR, "We cannot save your information, try later");
            }
        }

        DB::ref()->commit();
        return new FuxResponse(FuxResponse::SUCCESS, "Thank you for sharing your opinion");
    }

}