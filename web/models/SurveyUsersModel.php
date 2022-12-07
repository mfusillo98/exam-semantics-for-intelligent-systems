<?php

namespace App\Models;


/**
 * @property int survey_user_id,
 * @property float age,
 * @property int gender,
 * @property float height,
 * @property float weight,
 * @property string importance_sustainable_lifestyle,
 * @property string sustainability_of_your_lifestyle,
 * @property string sustainable_food_choices,
 * @property string look_nutritional_value_of_food_bought,
 * @property string employment,
 * @property string recipe_website_usage,
 * @property string preparing_home_cooked_meals,
 * @property string cooking_experience,
 * @property string max_cost,
 * @property string time_for_cooking,
 * @property string goal,
 * @property string mood,
 * @property string physical_activity,
 * @property string h_of_sleep,
 * @property string stressed,
 * @property string depressed,
 * @property string diabetes,
 * @property string pregnant,
 * @property string vegetarian,
 * @property string lactose_free,
 * @property string gluten_free,
 * @property string low_nickel,
 * @property string light_recipe,
 * @property string created_at,
 * @property string updated_at
 */


class SurveyUsersModel extends \Fux\Database\Model\Model
{

    protected static $tableName = 'survey_users';
    protected static $tableFields = ['survey_user_id', 'age', 'gender', 'height', 'weight', 'importance_sustainable_lifestyle', 'sustainability_of_your_lifestyle', 'sustainable_food_choices',
        'look_nutritional_value_of_food_bought', 'employment', 'recipe_website_usage', 'preparing_home_cooked_meals', 'cooking_experience',
        'max_cost', 'time_for_cooking', 'goal', 'mood', 'physical_activity', 'h_of_sleep', 'stressed', 'depressed', 'diabetes', 'pregnant', 'vegetarian', 'lactose_free',
        'gluten_free', 'low_nickel', 'light_recipe', 'created_at', 'updated_at'];
    protected static $primaryKey = ['survey_user_id'];
}

