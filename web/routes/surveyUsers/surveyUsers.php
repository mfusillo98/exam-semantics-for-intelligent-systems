<?php

\Fux\Routing\Routing::router()->get('/survey-users', function () {
    return \App\Controllers\SurveyUsers\SurveyUsersController::index();
});
