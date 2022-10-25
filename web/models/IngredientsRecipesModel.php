<?php

namespace App\Models;


/**
 * @property int recipe_id,
 * @property int ingredient_id,
 * @property int ingredient_index,
 */
class IngredientsRecipesModel extends \Fux\Database\Model\Model
{

    protected static $tableName = 'ingredients_recipes';
    protected static $tableFields = ['recipe_id', 'ingredient_id', 'ingredient_index'];
    protected static $primaryKey = ['recipe_id', 'ingredient_id'];
}
