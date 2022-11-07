<?php

\Fux\Routing\Routing::router()->get('/user/login', function () {
    return view("user/auth/login");
});


\Fux\Routing\Routing::router()->get('/user/signup', function () {
    return view("user/auth/signup");
});

\Fux\Routing\Routing::router()->get('/user/signup/get-ingredients', function (\Fux\Request $request) {
    return \App\Controllers\User\UserAuthController::getIngredients($request);
});