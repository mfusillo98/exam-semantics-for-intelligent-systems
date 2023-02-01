<?php

\Fux\Routing\Routing::router()->get('/dashboard', function (\Fux\Request $request) {
    return \App\Controllers\Dashboard\Dashboard::index($request);
});

\Fux\Routing\Routing::router()->get('/dashboard/survey/watable', function (\Fux\Request $request) {
    return \App\Controllers\Dashboard\Dashboard::surveyWatable($request);
});

\Fux\Routing\Routing::router()->get('/dashboard/survey/{survey_user_id}', function (\Fux\Request $request) {
    return \App\Controllers\Dashboard\Dashboard::surveyRowResume($request);
});

\Fux\Routing\Routing::router()->get('/dashboard/survey/{survey_user_id}/recipes/watable', function (\Fux\Request $request) {
    return \App\Controllers\Dashboard\Dashboard::surveyRowRecipesWatable($request);
});