<?php

namespace App\Controllers\User;

use App\Models\IngredientsModel;
use App\Models\IngredientsUserModel;
use App\Models\UsersModel;
use App\Packages\Auth\Auth;
use Fux\FuxQueryBuilder;

class UserDashboardController {

    public static function index(){
        $user = Auth::user(UsersModel::class);
        $user["km0_ingredients"] = (new FuxQueryBuilder())->select("*")
            ->from(IngredientsUserModel::class, "ui")
            ->leftJoin(IngredientsModel::class, "ui.ingredient_id = i.ingredient_id", "i")
            ->where("ui.type","km0")
            ->where("ui.user_id", $user["user_id"])
            ->execute();
        return view("user/dashboard/dashboard", ["user" => $user]);
    }

}