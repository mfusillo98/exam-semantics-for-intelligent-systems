<?php

namespace App\Middlewares;

use App\Models\UsersModel;

class UserDashboardLoggedInMiddleware extends \App\Packages\Auth\Middlewares\AuthLoggedInMiddleware
{
    protected $authenticatableClass = UsersModel::class;
    protected $redirectRoute = '/login';
}
