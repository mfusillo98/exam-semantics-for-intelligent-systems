<?php

use App\Recipes\RecipesConstants;

require_once __DIR__ . '/php/FuxFramework/bootstrap.php';

$surveys = \App\Models\SurveyUsersRecipesModel::listWhere("1");

foreach ($surveys as $s){
    $surveyNew = $s;
    echo "<br>Survey $s[survey_user_id], Tipo: $s[type]<br>";
    echo "Vecchio better: $surveyNew[better_recipe_id]";
    $surveyNew["better_recipe_id"] = in_array($s["chosen_recipe_id"], RecipesConstants::BEST_RECIPES[$s["type"]]) ? $s["chosen_recipe_id"] : $s["other_recipe_id"];
    echo "Nuovo better: $surveyNew[better_recipe_id]<br><br><br>";

    \App\Models\SurveyUsersRecipesModel::save([
        "survey_user_id" => $surveyNew["survey_user_id"],
        "chosen_recipe_id" => $surveyNew["chosen_recipe_id"],
        "other_recipe_id" => $surveyNew["other_recipe_id"],
        "better_recipe_id" => $surveyNew["better_recipe_id"]
    ]);
}

echo "SISTEMATI TUTTI!";