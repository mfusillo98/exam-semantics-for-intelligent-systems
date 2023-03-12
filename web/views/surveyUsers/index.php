<?php

/**
 * @var $recipes
 */

?>

<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- SITE TITTLE -->
    <title><?= PROJECT_NAME ?> | Your Recipes</title>
    <?= view('website/head') ?>

    <style>
        .ingredients-badge {
            font-size: 12px;
            background-color: #f0f3f5;
            padding: 5px;
            margin: 10px;
            cursor: pointer;
        }

        .ingredients-badge:hover {
            background-color: #e91e63;
            color: white;
        }

        .star-rating {
            font-size: 0;
            white-space: nowrap;
            display: inline-block;
            height: 50px;
            overflow: hidden;
            position: relative;
            background: url('data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iMjBweCIgaGVpZ2h0PSIyMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDIwIDIwIiB4bWw6c3BhY2U9InByZXNlcnZlIj48cG9seWdvbiBmaWxsPSIjREREREREIiBwb2ludHM9IjEwLDAgMTMuMDksNi41ODMgMjAsNy42MzkgMTUsMTIuNzY0IDE2LjE4LDIwIDEwLDE2LjU4MyAzLjgyLDIwIDUsMTIuNzY0IDAsNy42MzkgNi45MSw2LjU4MyAiLz48L3N2Zz4=');
            background-size: contain;
        }
        .star-rating i {
            opacity: 0;
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            /* width: 20%; remove this */
            z-index: 1;
            background: url('data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iMjBweCIgaGVpZ2h0PSIyMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDIwIDIwIiB4bWw6c3BhY2U9InByZXNlcnZlIj48cG9seWdvbiBmaWxsPSIjRkZERjg4IiBwb2ludHM9IjEwLDAgMTMuMDksNi41ODMgMjAsNy42MzkgMTUsMTIuNzY0IDE2LjE4LDIwIDEwLDE2LjU4MyAzLjgyLDIwIDUsMTIuNzY0IDAsNy42MzkgNi45MSw2LjU4MyAiLz48L3N2Zz4=');
            background-size: contain;
        }
        .star-rating input {
            -moz-appearance: none;
            -webkit-appearance: none;
            opacity: 0;
            display: inline-block;
            /* width: 20%; remove this */
            height: 100%;
            margin: 0;
            padding: 0;
            z-index: 2;
            position: relative;
        }
        .star-rating input:hover + i,
        .star-rating input:checked + i {
            opacity: 1;
        }
        .star-rating i ~ i {
            width: 40%;
        }
        .star-rating i ~ i ~ i {
            width: 60%;
        }
        .star-rating i ~ i ~ i ~ i {
            width: 80%;
        }
        .star-rating i ~ i ~ i ~ i ~ i {
            width: 100%;
        }
        ::after,
        ::before {
            height: 100%;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            text-align: center;
            vertical-align: middle;
        }

        .star-rating.star-5 {width: 250px;}
        .star-rating.star-5 input,
        .star-rating.star-5 i {width: 20%;}
        .star-rating.star-5 i ~ i {width: 40%;}
        .star-rating.star-5 i ~ i ~ i {width: 60%;}
        .star-rating.star-5 i ~ i ~ i ~ i {width: 80%;}
        .star-rating.star-5 i ~ i ~ i ~ i ~i {width: 100%;}
    </style>
</head>

<body class="sign-in-basic">

<!-- Navbar Transparent -->
<?= view('website/navbar') ?>
<!-- End Navbar -->

<div class="page-header align-items-start min-vh-100"
     style="background-image: url('<?= asset('img/surveyUsersImg.jpg') ?>'); padding-top: 10%; padding-bottom: 10%"
     loading="lazy">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container my-auto">
        <div class="row">
            <div class="col-md-8 col-12 mx-auto">
                <div class="card z-index-0 fadeIn3 fadeInBottom">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                            <h4 class="text-white font-weight-bolder text-center mt-2 mb-0" id="card-title">Build your User Profile</h4>
                        </div>
                    </div>
                    <div class="card-body page-inner">
                        <form role="form" class="text-start">
                            <input class="d-none" name="with_suggestion" value="<?= $withSuggestions ?>">

                            <!-- FIRST STEP-->
                            <div class="first-step">
                                <div class="w-100 text-center">
                                    In the following, we ask you to answer to some questions about you, your food choices and your lifestyle.<br>
                                    These data will be used to evaluate the sustainability of your food choices and the connections with your personal characteristics.
                                    <h4 class="my-3">Answer the following questions</h4>
                                </div>
                                <div class="row my-3 align-items-center">
                                    <div class="col-md-6 p-2">
                                        <div class="input-group input-group-outline">
                                            <label class="form-label">Age</label>
                                            <input id="age-input" type="number" class="form-control" name="age"
                                                   autocomplete="off">
                                        </div>
                                        <small id="age-error" class="text-danger d-none">To continue you must be almost
                                            18 years old</small>
                                    </div>
                                    <div class="col-md-6 p-2">
                                        <div class="input-group input-group-outline" autocomplete="off">
                                            <select class="form-control" name="gender" required>
                                                <option disabled selected value> Gender </option>
                                                <option value="1">Male</option>
                                                <option value="0">Female</option>
                                                <option value="-1">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 p-2">
                                        <div class="input-group input-group-outline">
                                            <label class="form-label">Height (cm)</label>
                                            <input type="number" class="form-control" name="height">
                                        </div>
                                    </div>
                                    <div class="col-md-6 p-2">
                                        <div class="input-group input-group-outline">
                                            <label class="form-label">Weight (kg)</label>
                                            <input type="number" class="form-control" name="weight">
                                        </div>
                                    </div>

                                    <!-- SUSTAINABLE FOOD CHOICE -->
                                    <div class="col-md-6 col-sm-3 mt-3 p-2">
                                        <div class="input-group input-group-outline">
                                            In your opinion, to make sustainable food choices is:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 mt-3 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="importance_sustainable_food_choice"
                                                    required>
                                                <option disabled selected value> </option>
                                                <option value="not_important">Not important</option>
                                                <option value="poorly_important">Poorly important</option>
                                                <option value="important">Important</option>
                                                <option value="very_important">Very important</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            How do you consider your food choices:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="sustainability_of_your_food_choice" required>
                                                <option disabled selected value> </option>
                                                <option value="dont_know">I don't know</option>
                                                <option value="absolutely_not_sustainable">Absolutely not sustainable</option>
                                                <option value="not_sustainable">Not sustainable</option>
                                                <option value="quite_sustainable">Quite sustainable</option>
                                                <option value="sustainable">Sustainable</option>
                                                <option value="very_sustainable">Very sustainable</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            I try to make sustainable food choices:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="sustainable_food_choices" required>
                                                <option disabled selected value> </option>
                                                <option value="always">Always</option>
                                                <option value="often">Often</option>
                                                <option value="usually">Usually</option>
                                                <option value="rarely">Rarely</option>
                                                <option value="never">Never</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- HEALTHY FOOD CHOICE -->
                                    <div class="col-md-6 col-sm-3 mt-3 p-2">
                                        <div class="input-group input-group-outline">
                                            In your opinion, to make healthy food choices is:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 mt-3 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="importance_healthy_lifestyle" required>
                                                <option disabled selected value> </option>
                                                <option value="not_important">Not important</option>
                                                <option value="poorly_important">Poorly important</option>
                                                <option value="important">Important</option>
                                                <option value="very_important">Very important</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            How do you consider your food choices:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="healthy_of_your_lifestyle" required>
                                                <option disabled selected value> </option>
                                                <option value="dont_know">I don't know</option>
                                                <option value="absolutely_not_healthy">Absolutely not healthy</option>
                                                <option value="not_healthy">Not healthy</option>
                                                <option value="quite_healthy">Quite healthy</option>
                                                <option value="healthy">Healthy</option>
                                                <option value="very_healthy">Very healthy</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            I try to make healthy food choices:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="healthy_food_choices" required>
                                                <option disabled selected value> </option>
                                                <option value="always">Always</option>
                                                <option value="often">Often</option>
                                                <option value="usually">Usually</option>
                                                <option value="rarely">Rarely</option>
                                                <option value="never">Never</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- IMPIEGO-->
                                    <div class="col-md-6 col-sm-3 mt-4 p-2">
                                        <div class="input-group input-group-outline">
                                            Employment:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 mt-4 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="employment" required>
                                                <option disabled selected value> </option>
                                                <option value="student">Student</option>
                                                <option value="private_company_staff">Provate company staff</option>
                                                <option value="public_company_staff">Public company staff</option>
                                                <option value="self_employed">Self employed</option>
                                                <option value="unemployed">Unemployed</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            Recipe website usage:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="recipe_website_usage" required>
                                                <option disabled selected value> </option>
                                                <option value="daily">Daily</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="monthly">Monthly</option>
                                                <option value="rarely">Rarely</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            Frequency of preparing home-cooked meals:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="preparing_home_cooked_meals" required>
                                                <option disabled selected value> </option>
                                                <option value="daily">Daily</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="monthly">Monthly</option>
                                                <option value="rarely">Rarely</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            Do you have any personal goal?
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="goal" required>
                                                <option disabled selected value> </option>
                                                <option value="loes_weight">Lose weight</option>
                                                <option value="gain_weight">Gain weight</option>
                                                <option value="no_goals">No goals</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="button" onclick="changeStep('second-step')"
                                            class="btn bg-gradient-primary w-100 my-4 mb-2">Next step (1 of 4)
                                    </button>
                                </div>
                            </div>


                            <!-- SECOND STEP-->
                            <div class="second-step d-none">
                                <a class="text-primary" onclick="changeStep('first-step')" style="cursor: pointer"><
                                    Prev step</a>
                                <div class="w-100 text-center my-3">
                                    Now, we ask you to guess the more sustainable recipe between the alternatives we show you.
                                </div>

                                <div id="firsts-container">
                                    <!--Populated by js-->
                                </div>

                                <div class="text-center">
                                    <button type="button" onclick="changeStep('third-step')"
                                            class="btn bg-gradient-primary w-100 my-4 mb-2">Next step (2 of 4)
                                    </button>
                                </div>
                            </div>

                            <!-- THIRD STEP -->
                            <div class="third-step d-none">
                                <a class="text-primary" onclick="changeStep('second-step')" style="cursor: pointer"><
                                    Prev step</a>

                                <div id="seconds-meat-container">
                                    <!--Populated by js-->
                                </div>

                                <div class="text-center">
                                    <button type="button" onclick="changeStep('fourth-step')"
                                            class="btn bg-gradient-primary w-100 my-4 mb-2">Next step (3 of 4)
                                    </button>
                                </div>
                            </div>

                            <!-- FOURTH STEP -->
                            <div class="fourth-step d-none">
                                <a class="text-primary" onclick="changeStep('third-step')" style="cursor: pointer"><
                                    Prev step</a>

                                <div id="desserts-container">
                                    <!--Populated by js-->
                                </div>

                                <div class="text-center">
                                    <button type="button" onclick="saveData()"
                                            class="btn bg-gradient-primary w-100 my-4 mb-2">Submit
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer position-absolute bottom-2 py-2 w-100">
        <div class="container">
            <div class="row align-items-center justify-content-lg-between">
                <div class="col-12 col-md-6 my-auto">
                    <div class="copyright text-center text-sm text-white text-lg-start">
                        ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                        ,
                        made with <i class="fa fa-heart" aria-hidden="true"></i> by Uniba
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                        <li class="nav-item">
                            <a href="<?= routeFullUrl('/') ?>" class="nav-link text-white">Homepage</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>

<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/core/popper.min.js', 'script') ?>
<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/core/bootstrap.min.js', 'script') ?>
<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/plugins/perfect-scrollbar.min.js', 'script') ?>
<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/plugins/parallax.min.js', 'script') ?>
<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/material-kit.min.js?v=3.0.0', 'script') ?>

<?= assetOnce('/lib/FuxFramework/AsyncCrud.js', "script") ?>
<?= assetOnce('/lib/FuxFramework/FuxUtility.js', "script") ?>
<?= assetOnce('/lib/FuxFramework/FuxHTTP.js', "script") ?>
<?= assetOnce('/lib/FuxFramework/FuxSwalUtility.js', "script") ?>
<?= assetOnce('/lib/FuxFramework/FuxCursorPaginator.js', "script") ?>
<?= assetOnce('/lib/moment/moment.js', "script") ?>

<!-- Bootstrap select -->
<link rel="stylesheet" href="<?= asset("lib/bootstrap-select/bootstrap-select.min.css") ?>">
<?= assetOnce('lib/popper/popper-2.11.5.min.js', 'script'); ?>
<script defer src="<?= asset("lib/bootstrap-select/bootstrap-select.min.js") ?>"></script>


<script>
    $('#confirm_password').on('keyup', function () {
        if ($('#password').val() === $('#confirm_password').val()) {
            $('#password_message').html('Le password coincidono').css('color', 'green');
        } else
            $('#password_message').html('Le password non coincidono').css('color', 'red');
    });
</script>

<!-- Age check -->
<script>
    $("#age-input").on('keyup', function () {
        if ($('#age-input').val() < 18) {
            $('#age-error').removeClass("d-none")
        } else
            $('#age-error').addClass("d-none")
    });
</script>

<script>

    function changeStep(step) {
        let cardTitleContainer = document.getElementById("card-title");
        cardTitleContainer.innerText = ""

        //Verify fields
        switch (step) {

            case 'first-step':
                cardTitleContainer.innerHTML = 'User Model'
                break

            case 'second-step':
                if(!challengeStepCheck("first-step")) return false
                cardTitleContainer.innerHTML = 'Select the more sustainable recipe'
                break

            case 'third-step':
                if(!challengeStepCheck("second-step")) return false
                cardTitleContainer.innerHTML = 'Select the more sustainable recipe'
                break

            case 'fourth-step':
                if(!challengeStepCheck("third-step")) return false
                cardTitleContainer.innerHTML = 'Select the more sustainable recipe'
                break
        }

        let steps = ["first-step", "second-step", "third-step", "fourth-step"]

        for (let i = 0; i < steps.length; i++) {
            if (step === steps[i]) {
                console.log(steps[i])
                $('.' + steps[i]).removeClass(" d-none ")
            } else {
                $('.' + steps[i]).addClass(" d-none ")
            }
        }
    }

    function challengeStepCheck(step){
        let error = "";

        let height = 1
        let weight = 1

        let listOfRadioChecked = 0
        let foundAlmostASelect = 0

        $("."+step+"").find(':input').not(':input[type=button], :input[type=submit]').each(function () {
            $(this).removeClass("border-danger")

            if(($(this)[0].name === 'height' && ($(this)[0].value < 50 || $(this)[0].value > 280))){
                height = 0
            }

            if(($(this)[0].name === 'weight' && ($(this)[0].value < 30 || $(this)[0].value > 280))){
                weight = 0
            }


            if(($(this)[0].type !== 'radio' && $(this)[0].type !== 'hidden' && !$(this)[0].value)){
                $(this).addClass("border-danger")
                error = 1
            }

            if(($(this)[0].type === 'radio' && $(this)[0].checked)){
                listOfRadioChecked += 1
            }

            if(($(this)[0].type === 'checkbox' && $(this)[0].checked)){
                foundAlmostASelect = 1
            }
        });


        if(!height) {FuxSwalUtility.error("Set a a valid input for height field"); return false}
        if(!weight) {FuxSwalUtility.error("Set a a valid input for weight field"); return false}

        if(error) {FuxSwalUtility.error("Set an input for all fields"); return false}
        if(!foundAlmostASelect && step !== 'first-step') {FuxSwalUtility.error("Select a reason for `Why did you consider it as more sustainable?`"); return false}
        if(listOfRadioChecked < 5 && step !== 'first-step') {FuxSwalUtility.error("Select a value for all stars input"); return false}

        return true
    }



    $(document).ready(function () {
        $("#firsts-container").append(recipeChoiceView("firsts", JSON.parse(`<?=$recipes["best_recipes"]["firsts"]?>`), JSON.parse(`<?=$recipes["worst_recipes"]["firsts"]?>`), <?=$withSuggestions?>))
        $("#seconds-meat-container").append(recipeChoiceView("seconds_meat", JSON.parse(`<?=$recipes["best_recipes"]["seconds_meat"]?>`), JSON.parse(`<?=$recipes["worst_recipes"]["seconds_meat"]?>`), <?=$withSuggestions?>))
        $("#desserts-container").append(recipeChoiceView("desserts", JSON.parse(`<?=$recipes["best_recipes"]["desserts"]?>`), JSON.parse(`<?=$recipes["worst_recipes"]["desserts"]?>`), <?=$withSuggestions?>))
    })

    function shuffle(array) {
        array.sort(() => Math.random() - 0.5);
    }

    /**
     * Build html to print in page to allows user to chose between two plates
     */
    function recipeChoiceView(type, goodRecipe, worstRecipe, withSuggestions) {
        let recipes = [goodRecipe, worstRecipe]
        shuffle(recipes)

        return `<div class="container mt-3">
                    <div class="row">
                        <div class="col-6">
                            ` + singleRecipeView(recipes[0], withSuggestions) + `
                        </div>
                        <div class="col-6">
                            ` + singleRecipeView(recipes[1], withSuggestions) + `
                        </div>

                        <div class="col-12 d-flex justify-content-between mt-5">
                            Given the ingredients, in your opinion, which recipe is more sustainable (e.g., lower carbon foot print)?
                            <div class="input-group input-group-outline">
                                <select name="${type}" class="form-control">
                                    <option disabled selected value> </option>
                                    <option value="${recipes[0].recipe_id + "_" + recipes[1].recipe_id}">Left</option>
                                    <option value="${recipes[1].recipe_id + "_" + recipes[0].recipe_id}">Right</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-5"/>

                        <div class="col-12">
                            <div class="col-12 text-center mb-5">
                                <h4>Give us more information!</h4>
                                <span>Please share the reasons that drove your choice</span>
                            </div>

                            <div class="row my-3">
                                <div class="col-5"><b>Why did you consider it as more sustainable? (You can select more than one reason)</b></div>
                                <div class="col-7">
                                    <div class="form-group">
                                        <input type="checkbox" name="${type}_why_selection_personal_knowledge" value="1"><label>My selection is based on personal knowledge (e.g., ingredients of the recipe)</label></input><br>
                                        <input type="checkbox" name="${type}_why_selection_intuition" value="1"><label>My selection is based on simple intuition</label></input><br>
                                        <input type="checkbox" name="${type}_why_selection_ui" value="1"><label>My selection is based on the information provided by the User Interface (UI)</label></input><br>
                                        <input type="checkbox" name="${type}_why_selection_chance" value="1"><label>My selection is made by chance</label></input>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-5"/>

                            <div class="col-12 text-center mb-3">
                                <h4>Select the recipe you would prefer to cook</h4>
                                <div class="input-group input-group-outline">
                                    <select name="${type}_favorite_to_cook" class="form-control">
                                        <option disabled selected value> </option>
                                        <option value="${recipes[0].recipe_id + "_" + recipes[1].recipe_id}">Left</option>
                                        <option value="${recipes[1].recipe_id + "_" + recipes[0].recipe_id}">Right</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 text-center mb-2">
                                <h4>Why?</h4>
                            </div>

                            <div class="row my-3">
                                <div class="col-md-5 col-12"><b>It matches my food tastes and preferences</b></div>
                                <div class="col-md-7 col-12 text-center">
                                <input type="hidden" name="${type}_matches_preferences" value=""><i></i>
                                <span class="star-rating star-5">
                                    <input type="radio" name="${type}_matches_preferences" value="1"><i></i>
                                    <input type="radio" name="${type}_matches_preferences" value="2"><i></i>
                                    <input type="radio" name="${type}_matches_preferences" value="3"><i></i>
                                    <input type="radio" name="${type}_matches_preferences" value="4"><i></i>
                                    <input type="radio" name="${type}_matches_preferences" value="5"><i></i>
                                </span>
                                </div>
                            </div>

                            <div class="row my-3">
                                <div class="col-md-5 col-12"><b>It seems savory and tastier</b></div>
                                <div class="col-md-7 col-12 text-center">
                                <input type="hidden" name="${type}_tastier" value=""><i></i>
                                <span class="star-rating star-5">
                                    <input type="radio" name="${type}_tastier" value="1"><i></i>
                                    <input type="radio" name="${type}_tastier" value="2"><i></i>
                                    <input type="radio" name="${type}_tastier" value="3"><i></i>
                                    <input type="radio" name="${type}_tastier" value="4"><i></i>
                                    <input type="radio" name="${type}_tastier" value="5"><i></i>
                                </span>
                                </div>
                            </div>

                            <div class="row my-3">
                                <div class="col-md-5 col-12"><b>It helps me to eat more healthily</b></div>
                                <div class="col-md-7 col-12 text-center">
                                <input type="hidden" name="${type}_helps_eat_healthily" value=""><i></i>
                                <span class="star-rating star-5">
                                    <input type="radio" name="${type}_helps_eat_healthily" value="1"><i></i>
                                    <input type="radio" name="${type}_helps_eat_healthily" value="2"><i></i>
                                    <input type="radio" name="${type}_helps_eat_healthily" value="3"><i></i>
                                    <input type="radio" name="${type}_helps_eat_healthily" value="4"><i></i>
                                    <input type="radio" name="${type}_helps_eat_healthily" value="5"><i></i>
                                </span>
                                </div>
                            </div>

                            <div class="row my-3">
                                <div class="col-md-5 col-12"><b>It helps me to eat in a more sustainable way</b></div>
                                <div class="col-md-7 col-12 text-center">
                                <input type="hidden" name="${type}_helps_eat_sustainable" value=""><i></i>
                                <span class="star-rating star-5">
                                    <input type="radio" name="${type}_helps_eat_sustainable" value="1"><i></i>
                                    <input type="radio" name="${type}_helps_eat_sustainable" value="2"><i></i>
                                    <input type="radio" name="${type}_helps_eat_sustainable" value="3"><i></i>
                                    <input type="radio" name="${type}_helps_eat_sustainable" value="4"><i></i>
                                    <input type="radio" name="${type}_helps_eat_sustainable" value="5"><i></i>
                                </span>
                                </div>
                            </div>

                            <div class="row my-3">
                                <div class="col-md-5 col-12"><b>It seems easier to prepare</b></div>
                                <div class="col-md-7 col-12 text-center">
                                <input type="hidden" name="${type}_easy_to_prepare" value=""><i></i>
                                <span class="star-rating star-5">
                                    <input type="radio" name="${type}_easy_to_prepare" value="1"><i></i>
                                    <input type="radio" name="${type}_easy_to_prepare" value="2"><i></i>
                                    <input type="radio" name="${type}_easy_to_prepare" value="3"><i></i>
                                    <input type="radio" name="${type}_easy_to_prepare" value="4"><i></i>
                                    <input type="radio" name="${type}_easy_to_prepare" value="5"><i></i>
                                </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>`
    }

    /**
     * Create one recipe card
     */
    function singleRecipeView(recipe, withSuggestions) {
        return `<div class="card text-center">
                    <div class="card-header p-3">
                        <h5>${recipe.title}</h5>
                    </div>
                    <div class="card-body py-3 pb-3">
                        ${recipe.ingredients_list.map(i => {
            let color = ""
            if (withSuggestions) {
                color = i.carbon_foot_print >= 0.7 ? "text-danger" : i.carbon_foot_print <= 0.3 ? "text-success" : ""
            }
            return `<span class="${color}">${i.name} </span>`
        })}
                    </div>
                </div>`
    }


    function saveData() {

        if(!challengeStepCheck("fourth-step")) return false

        let formData = {}
        $(".page-inner form").each(function () {
            $(this).find(':input').not(':input[type=button], :input[type=submit]').each(function () {
                if(($(this)[0].type === 'checkbox' || $(this)[0].type === 'radio') && !$(this)[0].checked){
                    return;
                }
                formData[$(this)[0].name] = $(this)[0].value
            })
        });

        FuxHTTP.post('<?=routeFullUrl('/survey-users/save')?>', formData, FuxHTTP.RESOLVE_DATA, FuxHTTP.REJECT_MESSAGE)
            .then(data => window.location.href = "<?=routeFullUrl('/survey-users/thank-you-page/')?>"+data.control_code)
            .catch(msg => FuxSwalUtility.error(msg))

    }

</script>

</body>

</html>


