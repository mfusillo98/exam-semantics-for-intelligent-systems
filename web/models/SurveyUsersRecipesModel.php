<?php


namespace App\Models;


/**
 * @property int survey_user_id,
 * @property string type,
 * @property int chosen_recipe_id,
 * @property int other_recipe_id,
 * @property int why_selection,
 * @property int favorite_to_cook,
 * @property int better_recipe_id,
 * @property int matches_preferences,
 * @property int tastier,
 * @property int helps_eat_healthily,
 * @property int helps_eat_sustainable,
 * @property int easy_to_prepare,
 */
class SurveyUsersRecipesModel extends \Fux\Database\Model\Model
{

    protected static $tableName = 'survey_users_recipes';
    protected static $tableFields = ['survey_user_id', 'type', 'chosen_recipe_id', 'other_recipe_id', 'why_selection', 'favorite_to_cook', 'better_recipe_id',
        'matches_preferences', 'tastier', 'helps_eat_healthily', 'helps_eat_sustainable', 'easy_to_prepare'
    ];
    protected static $primaryKey = ['survey_user_id', 'chosen_recipe_id', 'other_recipe_id'];
}

