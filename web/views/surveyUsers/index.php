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

        .rating {
            float:left;
            width:300px;
        }
        .rating span { float:right; position:relative; }
        .rating span input {
            position:absolute;
            top:0px;
            left:0px;
            opacity:0;
        }
        .rating span label {
            display:inline-block;
            width:30px;
            height:30px;
            text-align:center;
            color:#FFF;
            background:#ccc;
            font-size:30px;
            margin-right:2px;
            line-height:30px;
            border-radius:50%;
            -webkit-border-radius:50%;
        }
        .rating span:hover ~ span label,
        .rating span:hover label,
        .rating span.checked label,
        .rating span.checked ~ span label {
            background:#F90;
            color:#FFF;
        }
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
                            <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">User Models</h4>
                        </div>
                    </div>
                    <div class="card-body page-inner">
                        <form role="form" class="text-start">
                            <input class="d-none" name="with_suggestion" value="<?=$withSuggestions?>">

                            <!-- FIRST STEP-->
                            <div class="first-step">
                                <div class="w-100 text-center">
                                    Here you can answer to some question about your physic and your lifestyle and in the
                                    end
                                    we will ask to you if some recipes, in your opinion is healthy for our planet
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
                                                <option value="1">Male</option>
                                                <option value="0">Female</option>
                                                <option value="-1" selected>Other</option>
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

                                    <div class="col-md-6 col-sm-3 mt-3 p-2">
                                        <div class="input-group input-group-outline">
                                            In your opinion, to have a sustainable lifestyle is:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 mt-3 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="importance_sustainable_lifestyle"
                                                    required>
                                                <option value="not_important">Not important</option>
                                                <option value="poorly_important">Poorly important</option>
                                                <option value="important">Important</option>
                                                <option value="very_important">Very important</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            How do you consider your lifestyle:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="sustainability_of_your_lifestyle"
                                                    required>
                                                <option value="absolutely_not_healthy">Absolutely not sustainable
                                                </option>
                                                <option value="not_healthy">Not sustainable</option>
                                                <option value="quite_healthy">Quite sustainable</option>
                                                <option value="healthy">Sustainable</option>
                                                <option value="very_healthy">Very sustainable</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            I try to make sustainable food choices every day:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="sustainable_food_choices" required>
                                                <option value="always">Always</option>
                                                <option value="often">Often</option>
                                                <option value="usually">Usually</option>
                                                <option value="rarely">Rarely</option>
                                                <option value="never">Never</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 mt-4 p-2">
                                        <div class="input-group input-group-outline">
                                            Employment:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 mt-4 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="employment" required>
                                                <option value="student">Student</option>
                                                <option value="private_company_stuff">Provate company stuff</option>
                                                <option value="public_company_stuff">Public company stuff</option>
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
                                                <option value="daily">Daily</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="monthly">Monthly</option>
                                                <option value="rarely">Rarely</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            What is your goal?
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="goal" required>
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
                        made with <i class="fa fa-heart" aria-hidden="true"></i> by Salvathor & Fuso
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
        //Verify fields
        switch (step) {
            case 'first-step':
                break
            case 'second-step':
                if ($('#age-input').val() < 18) {
                    return FuxSwalUtility.error("Check age field")
                }
                if ($('input[name="height"]').val() < 50) {
                    return FuxSwalUtility.error("Check height field")
                }
                if ($('input[name="weight"]').val() < 30) {
                    return FuxSwalUtility.error("Check weight field")
                }
                break
            case 'third-step':
                console.log($('select[name="firsts"]').val())
                if (!$('select[name="firsts"]').val()) {
                    return FuxSwalUtility.error("Choose one")
                }
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
</script>

<script>
    $(document).ready(function () {
        $("#firsts-container").append(recipeChoiceView("firsts", JSON.parse('<?=$recipes["best_recipes"]["firsts"]?>'), JSON.parse('<?=$recipes["worst_recipes"]["firsts"]?>'), <?=$withSuggestions?>))
        $("#seconds-meat-container").append(recipeChoiceView("seconds_meat", JSON.parse('<?=$recipes["best_recipes"]["seconds_meat"]?>'), JSON.parse('<?=$recipes["worst_recipes"]["seconds_meat"]?>'), <?=$withSuggestions?>))
        $("#desserts-container").append(recipeChoiceView("desserts", JSON.parse('<?=$recipes["best_recipes"]["desserts"]?>'), JSON.parse('<?=$recipes["worst_recipes"]["desserts"]?>'), <?=$withSuggestions?>))
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
                            `+singleRecipeView(recipes[0], withSuggestions)+`
                        </div>
                        <div class="col-6">
                            `+singleRecipeView(recipes[1], withSuggestions)+`
                        </div>

                        <div class="col-12 d-flex justify-content-between mt-5">
                            Given ingredients, in your opinion, which recipe has a lower carbon food print?
                            <div class="input-group input-group-outline">
                                <select name="${type}" class="form-control">
                                    <option value="${recipes[0].recipe_id+"_"+recipes[1].recipe_id}">${recipes[0].title}</option>
                                    <option value="${recipes[1].recipe_id+"_"+recipes[0].recipe_id}">${recipes[1].title}</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-5"/>

                        <div class="col-12">
                            <div class="col-12 text-center mb-5">
                                <h4>Why did you choose this recipe?</h4>
                                <span>There is not correct answer, you have to choose following your way of thinking</span>
                            </div>
                            ${ratingInForm('This is a input type to get info', type+"question")}
                             ${ratingInForm('This is a input type to get info', type+"question")}
                             ${ratingInForm('This is a input type to get info', type+"question")}
                        </div>
                    </div>
                </div>`
    }

    /**
     * Create one recipe card
     */
    function singleRecipeView(recipe, withSuggestions){
        return `<div class="card text-center">
                    <div class="card-header p-3">
                        <h5>${recipe.title}</h5>
                    </div>
                    <div class="card-body py-3 pb-3">
                        ${recipe.ingredients_list.map(i =>{
                            let color = ""
                            if(withSuggestions){
                                color = i.carbon_foot_print >= 0.7 ? "text-danger" : i.carbon_foot_print <= 0.3 ? "text-success" : ""
                            }
                            return `<span class="${color}">${i.name} </span>`
                        })}
                    </div>
                </div>`
    }


    function ratingInForm(text, name){

        return `<div class="row my-2">
                    <div class="col-10">${text}</div>
                    <div class="col-2">
                        <div class="input-group input-group-outline">
                            <select name="${name}" class="form-control">
                                    <option value="5" selected>5</option>
                                    <option value="4">4</option>
                                    <option value="3">3</option>
                                    <option value="2">2</option>
                                    <option value="1">1</option>
                             </select>
                        </div>
                    </div>
                </div>`
    }
</script>

<script>
    function saveData() {
        let formData = {}
        $(".page-inner form").each(function () {
            $(this).find(':input').not(':input[type=button], :input[type=submit]').each(function () {
                if (!$(this)[0].value) {
                    FuxSwalUtility.error("Set an input for " + $(this)[0].name)
                    return 0;
                }
                formData[$(this)[0].name] = $(this)[0].value
            })
        });

        FuxHTTP.post('<?=routeFullUrl('/survey-users/save')?>', formData, FuxHTTP.RESOLVE_MESSAGE, FuxHTTP.REJECT_MESSAGE)
            .then(/*window.location.href = "routeFullUrl('/survey-users/thank-you-page')"*/)
            .catch(msg => FuxSwalUtility.error(msg))

    }

</script>

</body>

</html>


