<?php

namespace App\Models;


/**
 * @property int user_id,
 * @property int ingredient_id,
 * @property string type
 */
class IngredientsUserModel extends \Fux\Database\Model\Model
{

    protected static $tableName = 'ingredients_user';
    protected static $tableFields = ['user_id', 'ingredient_id', 'type'];
    protected static $primaryKey = ['user_id', 'ingredient_id', 'type'];
}
