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
        $withSuggestions =  rand(0,1);

        $recipes = [];

        //Get a random element for best recipes
        $recipes["best_recipes"]["firsts"] = RecipesUtils::getRecipeInformation(RecipesConstants::BEST_RECIPES["firsts"][array_rand(RecipesConstants::BEST_RECIPES["firsts"])]);
        $recipes["best_recipes"]["seconds_meat"] = RecipesUtils::getRecipeInformation(RecipesConstants::BEST_RECIPES["seconds_meat"][array_rand(RecipesConstants::BEST_RECIPES["seconds_meat"])]);
        $recipes["best_recipes"]["desserts"] = RecipesUtils::getRecipeInformation(RecipesConstants::BEST_RECIPES["desserts"][array_rand(RecipesConstants::BEST_RECIPES["desserts"])]);

        //Get a random element for worst recipes
        $recipes["worst_recipes"]["firsts"] = RecipesUtils::getRecipeInformation(RecipesConstants::WORST_RECIPES["firsts"][array_rand(RecipesConstants::WORST_RECIPES["firsts"])]);
        $recipes["worst_recipes"]["seconds_meat"] = RecipesUtils::getRecipeInformation(RecipesConstants::WORST_RECIPES["seconds_meat"][array_rand(RecipesConstants::WORST_RECIPES["seconds_meat"])]);
        $recipes["worst_recipes"]["desserts"] = RecipesUtils::getRecipeInformation(RecipesConstants::WORST_RECIPES["desserts"][array_rand(RecipesConstants::WORST_RECIPES["desserts"])]);


        return view('surveyUsers/index', ["recipes" => $recipes, "withSuggestions" => $withSuggestions]);
    }


    public static function save(Request $request){
        $body = $request->getBody();

        if(!isset($body["age"]) || $body["age"] < 18){
            return new FuxResponse(FuxResponse::ERROR, "Pay attention to the age field");
        }

        //Genero un control code finché diverso
        $controlCode = rand(100000,999999);
        while (SurveyUsersModel::getWhere(["control_code" => $controlCode])){
            $controlCode = rand(100000,999999);
        }
        $body["control_code"] = $controlCode;

        DB::ref()->begin_transaction();
        if(!$user_id = SurveyUsersModel::save($body)){
            DB::ref()->rollback();
            return new FuxResponse(FuxResponse::ERROR, "We cannot save your information, try later");
        }

        foreach (["firsts", "seconds_meat", "desserts"] as $type){
            $recipes = explode("_", $body[$type]);

            //Why selection string
            $whySelection = "";
            foreach (["personal_knowledge", "intuition", "ui", "chance"] as $why){
                $whySelection = isset($body[$type."_why_selection_".$why]) ? $whySelection.$why."," : $whySelection;
            }

            if(!SurveyUsersRecipesModel::save([
                "survey_user_id" => $user_id,
                "type" => $type,
                "chosen_recipe_id" => $recipes[0],
                "other_recipe_id" => $recipes[1],
                "why_selection" => $whySelection,
                "favorite_to_cook" => explode("_", $body[$type."_favorite_to_cook"])[0],
                "matches_preferences" => min($body[$type."_matches_preferences"], 5) ?? null,
                "tastier" => min($body[$type."_tastier"], 5) ?? null,
                "helps_eat_healthily" => min($body[$type."_helps_eat_healthily"], 5) ?? null,
                "helps_eat_sustainable" => min($body[$type."_helps_eat_sustainable"], 5) ?? null,
                "easy_to_prepare" => min($body[$type."_easy_to_prepare"], 5) ?? null,
                "better_recipe_id" => in_array($recipes[0], RecipesConstants::BEST_RECIPES) ? $recipes[0] : $recipes[1]
            ])){
                DB::ref()->rollback();
                return new FuxResponse(FuxResponse::ERROR, "We cannot save your information, try later");
            }
        }

        DB::ref()->commit();
        return new FuxResponse(FuxResponse::SUCCESS, "Thank you for sharing your opinion", ["control_code" => $controlCode]);
    }

}