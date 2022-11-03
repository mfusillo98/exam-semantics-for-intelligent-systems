<?php

\Fux\Routing\Routing::router()->get('/user/login', function () {
    return view("user/auth/login");
});