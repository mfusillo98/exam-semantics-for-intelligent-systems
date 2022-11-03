<?php

\Fux\Routing\Routing::router()->get('/', function () {
    return \App\Controllers\Website\WebsiteController::index();
});

\Fux\Routing\Routing::router()->get('/about', function () {
    return \App\Controllers\Website\WebsiteController::about();
});

\Fux\Routing\Routing::router()->get('/contact', function () {
    return \App\Controllers\Website\WebsiteController::contact();
});

\Fux\Routing\Routing::router()->get('/user', function () {
    return \App\Controllers\Website\WebsiteController::user();
});