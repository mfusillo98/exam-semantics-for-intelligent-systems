<?php


namespace App\Recipes;


use App\Models\IngredientsModel;
use App\Models\IngredientsRecipesModel;
use App\Models\RecipesModel;
use Fux\FuxQueryBuilder;

class RecipesUtils {

    /**
     * Get information about a recipe with all its ingredients
     * @param $recipe_id
     * @return RecipesModel|false|null
     */
    public static function getRecipeInformation($recipe_id){

        if(!$recipe = RecipesModel::get($recipe_id)) return false;

        $recipe["ingredients_list"] = (new FuxQueryBuilder())
            ->select("i.ingredient_id", "i.name", "i.carbon_foot_print")
            ->from(IngredientsRecipesModel::class,"ir")
            ->leftJoin(IngredientsModel::class, "ir.ingredient_id=i.ingredient_id", "i")
            ->where("ir.recipe_id", $recipe["recipe_id"])
            ->execute();

        return $recipe;
    }

}