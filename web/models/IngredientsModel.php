<?php

namespace App\Models;


/**
 * @property int ingredient_id,
 * @property string name,
 * @property int category_id,
 * @property float carbon_foot_print,
 * @property string carbon_foot_print_source,
 * @property string carbon_foot_print_weight,
 * @property float water_foot_print,
 * @property string water_foot_print_source,
 * @property string water_foot_print_weight,
 * @property float kcal,
 * @property float kcal_weight,
 * @property float protein,
 * @property float protein_weight,
 * @property float fat,
 * @property float fat_weight,
 * @property float carbohydrates,
 * @property float carbohydrates_weight,
 * @property float fiber,
 * @property float fiber_weight,
 * @property string vendor_recipe_ids,
 * @property string created_at,
 * @property string updated_ad
 */
class IngredientsModel extends \Fux\Database\Model\Model
{

    protected static $tableName = 'ingredients';
    protected static $tableFields = ['ingredient_id', 'name', 'category_id', 'carbon_foot_print', 'carbon_foot_print_source', 'carbon_foot_print_weight', 'water_foot_print',
        'water_foot_print_source', 'water_foot_print_weight', 'kcal', 'kcal_weight', 'protein', 'protein_weight', 'fat', 'fat_weight', 'carbohydrates', 'carbohydrates_weight',
        'fiber', 'fiber_weight', 'vendor_recipe_ids', 'created_at', 'updated_at'];
    protected static $primaryKey = ['ingredient_id'];
}
