<?php

\Fux\Routing\Routing::router()->get('/survey-users', function () {
    return \App\Controllers\SurveyUsers\SurveyUsersController::index();
});

\Fux\Routing\Routing::router()->post('/survey-users/save', function (\Fux\Request $request) {
    return \App\Controllers\SurveyUsers\SurveyUsersController::save($request);
});

\Fux\Routing\Routing::router()->get('/survey-users/thank-you-page/{control_code}', function (\Fux\Request $request) {
    return view("surveyUsers/thankYouPage", ["controlCode" => $request->getParams()["control_code"] ?? null]);
});
