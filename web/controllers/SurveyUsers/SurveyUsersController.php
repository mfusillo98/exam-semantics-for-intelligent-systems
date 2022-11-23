<?php

namespace App\Controllers\SurveyUsers;

use App\Models\IngredientsModel;
use App\Models\IngredientsUserModel;
use App\Models\UsersModel;
use App\Packages\Auth\Auth;
use Fux\DB;
use Fux\FuxResponse;
use Fux\Request;

class SurveyUsersController {

    /**
     * Returns survey page for users
     * @return string
     */
    public static function index()
    {
        return view('surveyUsers/index');
    }

}