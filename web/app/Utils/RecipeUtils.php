<?php

namespace App\Utils;

use App\Models\IngredientsModel;
use App\Models\IngredientsRecipesModel;
use App\Models\RecipesModel;
use Fux\FuxQueryBuilder;

class RecipeUtils
{

    public static function getMinMaxSustainabilityScore($disabled = null, $trust_cfp_min = 0.75, $trust_wfp_min = 0.75, $onlyWithRating = false)
    {

        $_qb = (new FuxQueryBuilder())
            ->select("SUM(i.carbon_foot_print_z_score + i.water_foot_print_z_score) as sustainability")
            ->from(RecipesModel::class, "r")
            ->leftJoin(IngredientsRecipesModel::class, "ir.recipe_id = r.recipe_id", "ir")
            ->leftJoin(IngredientsModel::class, "ir.ingredient_id = i.ingredient_id", "i")
        ->groupBy("r.recipe_id");

        if ($disabled !== null) $_qb->where("disabled", $disabled);
        if ($trust_cfp_min) $_qb->whereGreaterEqThan("r.trust_cfp", $trust_cfp_min);
        if ($trust_wfp_min) $_qb->whereGreaterEqThan("r.trust_wfp", $trust_wfp_min);
        if ($onlyWithRating) $_qb->whereNotNull("r.rating");

        return (new FuxQueryBuilder())
            ->select("MIN(t.sustainability) as min", "MAX(t.sustainability) as max")
            ->from($_qb, "t")
            ->first();
    }

}