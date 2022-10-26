<?php


\Fux\Routing\Routing::router()->get('/worst-recipes', function () {
    return \App\Controllers\Website\WorstRecipesController::index();
});