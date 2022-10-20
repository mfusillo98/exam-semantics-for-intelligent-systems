<?php

use Fux\FuxMiddleware;

class LoggedInMiddleware extends FuxMiddleware
{
    public function handle()
    {

        if (!\App\Packages\Auth\Auth::check(CredentialModel::class)){
            redirect('/login');
        }

        return $this->resolve();
    }
}
