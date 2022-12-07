<?php

\Fux\Routing\Routing::router()->get('/survey-users', function () {
    return \App\Controllers\SurveyUsers\SurveyUsersController::index();
});

\Fux\Routing\Routing::router()->post('/survey-users/save', function (\Fux\Request $request) {
    return \App\Controllers\SurveyUsers\SurveyUsersController::save($request);
});

\Fux\Routing\Routing::router()->get('/survey-users/thank-you-page', function (\Fux\Request $request) {
    return view("surveyUsers/thankYouPage");
});
