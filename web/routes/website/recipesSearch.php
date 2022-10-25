<?php

\Fux\Routing\Routing::router()->get('/recipes-search/do-search', function (\Fux\Request $request) {
    return \App\Controllers\Website\RecipesSearchController::doSearch($request);
});