<?php

namespace App\Models;


/**
 * @property int ingredient_id,
 * @property string name
 */
class IngredientsNameAliasModel extends \Fux\Database\Model\Model
{

    protected static $tableName = 'ingredients_name_alias';
    protected static $tableFields = ['ingredient_id', 'name'];
    protected static $primaryKey = ['ingredient_id'];
}
