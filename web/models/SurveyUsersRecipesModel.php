<?php


namespace App\Models;


/**
 * @property int survey_user_id,
 * @property string type,
 * @property int chosen_recipe_id,
 * @property int other_recipe_id,
 */
class SurveyUsersRecipesModel extends \Fux\Database\Model\Model
{

    protected static $tableName = 'survey_users_recipes';
    protected static $tableFields = ['survey_user_id', 'type', 'chosen_recipe_id', 'other_recipe_id'];
    protected static $primaryKey = ['survey_user_id', 'chosen_recipe_id', 'other_recipe_id'];
}

