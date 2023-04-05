<?php

namespace App\Controllers\Dashboard;

use App\Models\SurveyUsersModel;
use App\Models\SurveyUsersRecipesModel;
use Fux\DB;
use Fux\FuxQueryBuilder;
use Fux\FuxResponse;
use Fux\Request;
use WatableActions;
use WatableOrderings;
use WatableOverrides;
use WatableService;
use WatableTemplates;

class Dashboard {

    const DASHBOARD_ACCESS_CODE = "Y2FjY2FjYWNjYQ==";

    public static function index(Request $request){
        $qsParams = $request->getQueryStringParams();
        if(!isset($qsParams["accessCode"]) || $qsParams["accessCode"] !== self::DASHBOARD_ACCESS_CODE){
            return new FuxResponse(FuxResponse::ERROR, "Non puoi accedere a questa area");
        }
        return view("dashboard/dashboard");
    }

    public static function surveyWatable(Request $request){
        $qsParams = $request->getQueryStringParams();
        if(!isset($qsParams["accessCode"]) || $qsParams["accessCode"] !== self::DASHBOARD_ACCESS_CODE){
            return json_encode([]);
        }

        $model = SurveyUsersModel::class;
        $watable = new WatableService($model);

        //Override dei valori secondo alcune condizioni
        $override = new WatableOverrides();
        $watable->setOverrides($override);

        //Template dei campi
        $template = new WatableTemplates();
        $watable->setTemplates($template);

        //Pulsanti da mostrare in tabella
        $actions = array();
        $actions[] = new WatableActions("see", function ($row) {
            $url = routeFullUrl("/dashboard/survey/".$row["survey_user_id"]."?accessCode=".self::DASHBOARD_ACCESS_CODE);
            return "<a class='btn btn-warning btn-sm' href='$url' target='_blank'><i class='fas fa-eye'></i></a> ";
        });
        $watable->setActions($actions);

        //Campi del model da rimuovere
        $watable->removeFields(["updated_at"]);

        //Ordinamento dei campi
        $ordering = new WatableOrderings();
        $ordering->add("created_at", 2);
        $watable->setOrderings($ordering);

        //Output della tabella
        return json($watable->getJsonDataFromModel(
            function () use ($model) {
                return $model::listWhere("1");
            }
        ));
    }


    /**
     * Survey row view
     * @param Request $request
     * @return string
     */
    public static function surveyRowResume(Request $request){
        $qsParams = $request->getQueryStringParams();
        if(!isset($qsParams["accessCode"]) || $qsParams["accessCode"] !== self::DASHBOARD_ACCESS_CODE){
            return new FuxResponse(FuxResponse::ERROR, "Non puoi accedere a questa area");
        }

        $surveyUserId = $request->getParams()["survey_user_id"];
        return view("dashboard/surveyRow", ["surveyRow" => SurveyUsersModel::get($surveyUserId)]);
    }


    public static function surveyRowRecipesWatable(Request $request){

        $qsParams = $request->getQueryStringParams();
        if(!isset($qsParams["accessCode"]) || $qsParams["accessCode"] !== self::DASHBOARD_ACCESS_CODE){
            return new FuxResponse(FuxResponse::ERROR, "Non puoi accedere a questa area");
        }

        $surveyUserId = $request->getParams()["survey_user_id"];

        $model = SurveyUsersRecipesModel::class;
        $watable = new WatableService($model, $surveyUserId);

        //Override dei valori secondo alcune condizioni
        $override = new WatableOverrides();
        $override->add("perception_sustainability", function ($row, $key, $value) {
           return $value === $row["sustainable_recipe_id"] ? "1" : "0";
        });
        $override->add("preference_cooking", function ($row, $key, $value) {
           return $value === $row["sustainable_recipe_id"] ? "1" : "0";
        });
        $watable->setOverrides($override);

        //Template dei campi
        $template = new WatableTemplates();
        $watable->setTemplates($template);

        //Pulsanti da mostrare in tabella
        $actions = array();
        $actions[] = new WatableActions("see", function ($row) {
            $url = routeFullUrl("/dashboard/survey/".$row["survey_user_id"]);
            return "<a class='btn btn-warning btn-sm' href='$url' target='_blank'><i class='fas fa-eye'></i></a> ";
        });
        $watable->setActions($actions);

        //Campi del model da rimuovere
        $watable->removeFields(["other_recipe_id"]);

        //Ordinamento dei campi
        $ordering = new WatableOrderings();
        $watable->setOrderings($ordering);

        //Output della tabella
        return json($watable->getJsonDataFromModel(
            function () use ($model, $surveyUserId) {
                return (new FuxQueryBuilder())
                ->select(
                    "survey_user_id, type, better_recipe_id as sustainable_recipe_id, 
                            IF(better_recipe_id = chosen_recipe_id, other_recipe_id, chosen_recipe_id) as worst_recipe_id, 
                            chosen_recipe_id as perception_sustainability,
                            favorite_to_cook as preference_cooking,
                            reason_knowledge, reason_ui, reason_chance, reason_intuition,
                            matches_preferences, tastier, helps_eat_healthily, helps_eat_sustainable, easy_to_prepare
                            ")
                ->from($model)
                ->where("survey_user_id", $surveyUserId)
                ->execute();
            }
        ));
    }
}