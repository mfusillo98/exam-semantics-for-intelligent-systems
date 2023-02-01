<?php

namespace App\Models;


/**
 * @property int survey_user_id,
 * @property float age,
 * @property int gender,
 * @property float height,
 * @property float weight,
 * @property string importance_sustainable_food_choice,
 * @property string sustainability_of_your_food_choice,
 * @property string sustainable_food_choices,
 * @property string importance_healthy_lifestyle,
 * @property string healthy_of_your_lifestyle,
 * @property string healthy_food_choices,
 * @property string employment,
 * @property string recipe_website_usage,
 * @property string preparing_home_cooked_meals,
 * @property string goal,
 * @property string created_at,
 * @property string updated_at,
 * @property string with_suggestion,
 */


class SurveyUsersModel extends \Fux\Database\Model\Model
{

    protected static $tableName = 'survey_users';
    protected static $tableFields = ['survey_user_id', 'age', 'gender', 'height', 'weight',
        'importance_sustainable_food_choice', 'sustainability_of_your_food_choice', 'sustainable_food_choices',
        'importance_healthy_lifestyle', 'healthy_of_your_lifestyle', 'healthy_food_choices',
        'employment', 'recipe_website_usage', 'preparing_home_cooked_meals',
        'created_at', 'updated_at', 'with_suggestion', 'control_code'];
    protected static $primaryKey = ['survey_user_id'];
}

