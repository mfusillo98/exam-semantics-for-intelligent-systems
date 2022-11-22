<?php

\Fux\Routing\Routing::router()->withMiddleware(new App\Packages\Auth\Middlewares\AuthLoggedInMiddleware(), function (){

    \Fux\Routing\Routing::router()->get('/user/dashboard', function (){
       return \App\Controllers\User\UserDashboardController::index();
    });

});