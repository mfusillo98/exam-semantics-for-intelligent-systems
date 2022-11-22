<?php

namespace App\Controllers\User;

use App\Models\IngredientsModel;
use App\Models\IngredientsUserModel;
use App\Models\UsersModel;
use App\Packages\Auth\Auth;
use Fux\DB;
use Fux\FuxResponse;
use Fux\Request;

class UserAuthController {

    /**
     * Execute a login attempt. Return the redirect URL in case of success
     *
     * @param Request $request
     *
     * @return FuxResponse
     *
     * @throws \App\Packages\Auth\Exceptions\InvalidCredentialsException
     */
    public static function doLogin(Request $request)
    {

        /**
         * @var array $body = [
         *     "username" => "salvo",
         *     "password" => "plain-text-pw"
         * ]
         */
        $body = $request->getBody();

        if (Auth::attempt(UsersModel::class, [
            "username" => $body['username'],
            "password" => $body['password'],
        ])) {
            return new FuxResponse(FuxResponse::SUCCESS, null, routeFullUrl('/user/dashboard'));
        }

        return new FuxResponse(FuxResponse::ERROR, "Non è stato possibile completare il login");
    }


    /**
     * Execute a logout action and redirect to login page
     */
    public static function doLogout()
    {
        Auth::logout(StudentsModel::class);
        redirect(STUDENT_DASHBOARD_PACKAGE['BASE_ROUTE'] . '/login');
    }


    public static function getIngredients(Request $request){
        $query = $request->getQueryStringParams()["query"];
        return new FuxResponse(FuxResponse::SUCCESS, "Ingredients taken", ["ingredients" => IngredientsModel::listWhere("name like '%$query%'")]);
    }

    /**
     * Users signup and save data
     * @param Request $request
     */
    public static function signUp(Request $request){
        /**
         * @var array $body = [
         *     "username" => "salvo",
         *     "password" => "password123",
         *     "confirm_password" => "password123",
         *     "age" => 12,
         *     "gender" => 1,
         *     "height" => 160.30,
         *     "weight" => 57.3,
         *     "ingredients" => [1,2,3]
         * ]
         */

        $body = $request->getBody();

        //Check obligatory fields
        $requiredFields = ["username", "password", "confirm_password", "age", "gender", "height", "weight"];
        foreach ($requiredFields as $keyName) {
            if (!trim($body[$keyName])) {
                return new FuxResponse(FuxResponse::ERROR, "Check that all obligatory fields are compiled");
            }
        }

        //Controllo se le due password coincidono
        if($body["password"] !== $body["confirm_password"]){
            return new FuxResponse(FuxResponse::ERROR, "Passwords and not match!");
        }

        //Criptazione della password
        $body["password"] = password_hash(html_entity_decode($body['password']), PASSWORD_BCRYPT);

        //Verifico che la email non sia già in uso
        if (UsersModel::getWhere(["username" => $body["username"]])) {
            return new FuxResponse(FuxResponse::ERROR, "This username is already used");
        }

        //Check if parametric value are compiled correctly
        if(!is_numeric($body["age"]) || !is_numeric($body["height"]) || !is_numeric($body["weight"])){
            return new FuxResponse(FuxResponse::ERROR, "Attention, not all fields are compiled correctly, check and try again");
        }

        //Check gender
        if($body["gender"] !== "0" && $body["gender"] !== "1"){
            return new FuxResponse(FuxResponse::ERROR, "Please check your gender selection");
        }

        DB::ref()->begin_transaction();
        if(!$user_id = UsersModel::save(
            ["username" => $body["username"], "password" => $body["password"], "gender" => $body["gender"],
                "age" => $body["age"], "height" => $body["height"], "weight" => $body["weight"]]
        )){
            DB::ref()->rollback();
            return new FuxResponse(FuxResponse::ERROR, "Attenzione non siamo riusciti a salvare le tue informazioni, riprova");
        }

        foreach ($body['ingredients'] as $ingredient_id){
            if(!IngredientsUserModel::save(["user_id" => $user_id, "ingredient_id" => $ingredient_id, "type" => "km0"])){
                DB::ref()->rollback();
                return new FuxResponse(FuxResponse::ERROR, "It's not been possible save you ingredients try again");
            }
        }

        DB::ref()->commit();
        return new FuxResponse(FuxResponse::SUCCESS,"Your registration is been completed correctly!");
    }

}