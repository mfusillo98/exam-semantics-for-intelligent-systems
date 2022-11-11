<?php

namespace App\Packages\Auth\Middlewares;

use App\Models\UsersModel;
use Fux\FuxMiddleware;


class AuthLoggedInMiddleware extends FuxMiddleware
{

    protected $authenticatableClass = UsersModel::class;
    protected $redirectRoute = '';

    public function handle()
    {

        if (!\App\Packages\Auth\Auth::check($this->authenticatableClass)) {
            redirect($this->redirectRoute);
        }

        return $this->resolve();
    }
}
