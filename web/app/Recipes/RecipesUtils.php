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

        $ingredientsSelect =
            "CONCAT('
                {ingredients_list:[', 
                GROUP_CONCAT(DISTINCT CONCAT('{name:`', i.name, '`, carbon_foot_print:', i.carbon_foot_print, '}') SEPARATOR ','), 
                ']}') as ingredients_list";

        $additionalInfo = (new FuxQueryBuilder())->select(
            $ingredientsSelect,
            "SUM(i.carbon_foot_print_z_score + i.water_foot_print_z_score) as static_score")
            ->from(IngredientsRecipesModel::class, "ir")
            ->leftJoin(IngredientsModel::class, "ir.ingredient_id=i.ingredient_id", "i")
            ->where("ir.recipe_id", $recipe_id)
            ->groupBy("ir.recipe_id")
            ->execute()[0];

        $recipe["ingredients_list"] = json_decode($additionalInfo["ingredients_list"], true);
        $recipe["static_score"] = $additionalInfo["static_score"];

        return $recipe;
    }

}