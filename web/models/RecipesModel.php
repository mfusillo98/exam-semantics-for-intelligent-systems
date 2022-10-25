<?php

namespace App\Models;


/**
 * @property int recipe_id,
 * @property string title,
 * @property string url,
 * @property string vendor_id,
 * @property float static_score,
 * @property float mcfp,
 * @property float trust_cfp,
 * @property float mwfp,
 * @property float trust_wfp,
 * @property string created_at,
 * @property string updated_ad
 */
class RecipesModel extends \Fux\Database\Model\Model
{

    protected static $tableName = 'recipes';
    protected static $tableFields = ['recipe_id', 'title', 'url', 'vendor_id', 'static_score', 'mcfp', 'trust_cfp',
        'mwfp', 'trust_wfp', 'created_at', 'updated_at'];
    protected static $primaryKey = ['recipe_id'];
}
