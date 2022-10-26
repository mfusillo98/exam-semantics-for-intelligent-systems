<?php


\Fux\Routing\Routing::router()->get('/top-recipes', function () {
    return \App\Controllers\Website\TopRecipesController::index();
});