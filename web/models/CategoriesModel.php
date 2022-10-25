<?php

namespace App\Models;


/**
 * @property int category_id,
 * @property string name,
 * @property string created_at,
 * @property string updated_ad
 */
class CategoriesModel extends \Fux\Database\Model\Model
{

    protected static $tableName = 'categories';
    protected static $tableFields = ['category_id', 'name', 'created_at', 'updated_at'];
    protected static $primaryKey = ['category_id'];
}
