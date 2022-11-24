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

        $additionalInfo = (new FuxQueryBuilder())->select("GROUP_CONCAT(DISTINCT i.name, ' | ') as ingredients_list", "SUM(i.carbon_foot_print_z_score + i.water_foot_print_z_score) as static_score")
            ->from(IngredientsRecipesModel::class, "ir")
            ->leftJoin(IngredientsModel::class, "ir.ingredient_id=i.ingredient_id", "i")
            ->where("ir.recipe_id", $recipe_id)
            ->groupBy("ir.recipe_id")
            ->execute()[0];

        $recipe["ingredients_list"] = $additionalInfo["ingredients_list"];
        $recipe["static_score"] = $additionalInfo["static_score"];

        return $recipe;
    }

}