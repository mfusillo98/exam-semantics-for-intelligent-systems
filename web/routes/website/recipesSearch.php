<?php

\Fux\Routing\Routing::router()->get('/recipes-search/by-ingredients', function (\Fux\Request $request) {
    return \App\Controllers\Website\RecipesSearchController::searchByIngredients($request);
});

\Fux\Routing\Routing::router()->get('/recipes-search/by-name', function (\Fux\Request $request) {
    return \App\Controllers\Website\RecipesSearchController::searchByName($request);
});
