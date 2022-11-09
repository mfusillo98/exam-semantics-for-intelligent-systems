<?php

namespace App\Models;


/**
 * @property int user_id,
 * @property string username,
 * @property string password,
 * @property float age,
 * @property int gender,
 * @property float height,
 * @property float weight,
 * @property string created_at,
 * @property string updated_at
 */
class UsersModel extends \Fux\Database\Model\Model
{

    protected static $tableName = 'users';
    protected static $tableFields = ['user_id', 'username', 'password', 'age', 'gender', 'height', 'weight', 'created_at', 'updated_at'];
    protected static $primaryKey = ['user_id'];
}
