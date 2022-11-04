<?php

\Fux\Routing\Routing::router()->get('/user/login', function () {
    return view("user/auth/login");
});


\Fux\Routing\Routing::router()->get('/user/signup', function () {
    return view("user/auth/signup");
});