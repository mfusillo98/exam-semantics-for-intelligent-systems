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

        .ingredients-badge:hover{
            background-color: #e91e63;
            color: white;
        }
    </style>
</head>

<body class="sign-in-basic">

<!-- Navbar Transparent -->
<?= view('website/navbar') ?>
<!-- End Navbar -->

<div class="page-header align-items-start min-vh-100"
     style="background-image: url('<?= asset('img/surveyUsersImg.jpg') ?>'); padding-top: 10%; padding-bottom: 10%" loading="lazy">
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
                            <div class="first-step">
                                <div class="w-100 text-center">
                                    Here you can answer to some question about your physic and your lifestyle and in the end
                                    we will ask to you if some recipes, in your opinion is healthy for our planet
                                    <h4 class="my-3">Answer the following questions</h4>
                                </div>
                                <div class="row my-3 align-items-center">
                                    <div class="col-md-6 p-2">
                                        <div class="input-group input-group-outline">
                                            <label class="form-label">Age</label>
                                            <input type="number" class="form-control" name="age" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6 p-2">
                                        <div class="input-group input-group-outline" autocomplete="off">
                                            <select class="form-control" name="gender" required>
                                                <option value="">Choose your gender</option>
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

                                    <div class="col-md-6 col-sm-3 mt-3 p-2">
                                        <div class="input-group input-group-outline">
                                            In your opinion, to have a healthy lifestyle is:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 mt-3 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="importance_healthy_lifestyle" required>
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
                                            <select class="form-control" name="healthy_of_your_lifestyle" required>
                                                <option value="absolutely_not_healthy">Absolutely not healthy</option>
                                                <option value="not_healthy">Not healthy</option>
                                                <option value="quite_healthy">Quite healthy</option>
                                                <option value="healthy">Healthy </option>
                                                <option value="very_healthy">Very healthy</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            I try to make healthy food choices every day:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="healthy_food_choices" required>
                                                <option value="always">Always</option>
                                                <option value="often">Often</option>
                                                <option value="usually">Usually</option>
                                                <option value="rarely">Rarely</option>
                                                <option value="never">Never</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            I look at the nutritional value of food products I buy:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="look_nutritional_value_of_food_bought" required>
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
                                                <option value="student">Always</option>
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
                                                <option value="student">Daily</option>
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
                                                <option value="student">Daily</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="monthly">Monthly</option>
                                                <option value="rarely">Rarely</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 mt-4 p-2">
                                        <div class="input-group input-group-outline">
                                            Cooking experience:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 mt-4 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="cooking_experience" required>
                                                <option value="very_low">Very low</option>
                                                <option value="low">Low</option>
                                                <option value="medium">Medium</option>
                                                <option value="high">High</option>
                                                <option value="very_high">Very high</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            Maximum cost of the recipe:
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="max_cost" required>
                                                <option value="very_low">Very low</option>
                                                <option value="low">Low</option>
                                                <option value="medium">Medium</option>
                                                <option value="high">High</option>
                                                <option value="not_important">Not important</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-6 p-2 my-4">
                                        <div class="input-group input-group-outline">
                                            Time Available for Cooking (max time in min. 0 = no constraints):
                                        </div>
                                    </div>
                                    <div class="col-6 p-2">
                                        <div class="input-group input-group-outline">
                                            <input type="number" class="form-control" name="time_for_cooking" value="0" autocomplete="off">
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

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            What is your mood right now?
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="mood" required>
                                                <option value="good">Good</option>
                                                <option value="neutral">Neutral</option>
                                                <option value="bad">Bad</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            How much physical activity do you do in a week?
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="physical_activity" required>
                                                <option value="9">A lot (>9)</option>
                                                <option value="6">Neutral (=6)</option>
                                                <option value="3">Not enough (<3)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            How many hours of sleep do you usually get?
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="h_of_sleep" required>
                                                <option value="min8">min of 8h</option>
                                                <option value="maj8">maj of 8h</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            Do you feel stressed?
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="stressed" required>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 p-2">
                                        <div class="input-group input-group-outline">
                                            Do you feel depressed?
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="depressed" required>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-3 mt-4 p-2">
                                        <div class="input-group input-group-outline">
                                            Select your restrictions
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-9 mt-4 p-2">
                                        <div class="form-check">
                                            <input type="checkbox" id="diabetes" name="diabetes">
                                            <label class="form-check-label" for="diabetes">Diabetes</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="pregnant" name="pregnant">
                                            <label class="form-check-label" for="pregnant">Pregnant or breastfeeding</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="vegetarian" name="vegetarian">
                                            <label class="form-check-label" for="vegetarian">Vegetarian</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="lactose-free" name="lactose-free">
                                            <label class="form-check-label" for="lactose-free">Lactose-free</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="gluten-free" name="gluten-free">
                                            <label class="form-check-label" for="gluten-free">Gluten-free</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="low-nickel" name="low-nickel">
                                            <label class="form-check-label" for="low-nickel">Low-Nickel</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="light-recipe" name="light-recipe">
                                            <label class="form-check-label" for="light-recipe">Light recipe</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="button" onclick="changeStep(2)" class="btn bg-gradient-primary w-100 my-4 mb-2">Next step (1 of 2)</button>
                                </div>
                            </div>

                            <div class="second-step d-none">
                                <a class="text-primary" onclick="changeStep(1)" style="cursor: pointer">< Prev step</a>
                                <div class="text-center">
                                    <h3 class="text-primary">Km 0 ingredients</h3>
                                    <span>In this section you should insert some ingredients that you can get at km 0, form your garden for example, or other same stuff</span>
                                </div>
                                <div class="input-group input-group-outline mt-2 ">
                                    <label class="form-label">Ingredients</label>
                                    <input type="text" class="form-control" name="ingredients" id="ingredientsBar">
                                </div>

                                <!-- chosen ingredients container -->
                                <div class="col-12 my-2 card card-body p-1 shadow-sm text-center" id="ingredientsChosenContainer">
                                    <small class="text-muted mt-2">Here you will see chosen ingredients...</small>
                                </div>

                                <!-- loader container -->
                                <div class="col-12 mt-4 loader d-none" id="loader-container"></div>

                                <!-- Ingredients container -->
                                <div class="my-2" id="ingredientsContainer"></div>

                                <div class="text-center">
                                    <button type="button" onclick="saveData()" class="btn bg-gradient-primary w-100 my-4 mb-2">Submit</button>
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
                        <li class="nav-item">
                            <a href="<?= routeFullUrl('/user') ?>" class="nav-link text-white" target="_blank">About
                                this section</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>

<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/core/popper.min.js', 'script')?>
<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/core/bootstrap.min.js', 'script')?>
<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/plugins/perfect-scrollbar.min.js', 'script')?>
<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/plugins/parallax.min.js', 'script')?>
<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/material-kit.min.js?v=3.0.0', 'script')?>

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

<script>
    function changeStep(step){
        switch (step){
            case 1:
                $('.first-step').removeClass(" d-none ")
                $('.second-step').addClass(" d-none ")
                break
            case 2:
                $('.first-step').addClass(" d-none ")
                $('.second-step').removeClass(" d-none ")
                break
        }
    }
</script>

<script>
    let typingTimer = null
    let doneTypingTimer = 300
    let chosenIngredients = []
    let foundIngredients = []

    //Gestisce il timeout della ricerca
    $('#ingredientsBar').on('keyup', function () {
        clearTimeout(typingTimer);
        if (this.value.length > 1) {
            typingTimer = setTimeout(_ => {
                getIngredients(this.value)
            }, doneTypingTimer);
        }else {
            let container = document.getElementById('ingredientsContainer')
            container.innerHTML = "";
        }
    });


    function getIngredients(query){
        FuxHTTP.get('<?=routeFullUrl('/user/signup/get-ingredients')?>', {query: query}, FuxHTTP.RESOLVE_DATA, FuxHTTP.REJECT_MESSAGE)
            .then(data =>{
                foundIngredients = data.ingredients
                printIngredients()
            })
    }

    function printIngredients(){
        $("#ingredientsContainer").empty()
        console.log("WE")
        foundIngredients.map(i =>{
            if(chosenIngredients.findIndex(ingredient =>{return ingredient.ingredient_id == i.ingredient_id}) < 0){
                $("#ingredientsContainer").append(`<span class='ingredients-badge' onclick='handlerChosenIngredient(${i.ingredient_id}, "${i.name}")'>${i.name}</span>`)
            }
        })
    }

    function printChosenIngredients(){
        $("#ingredientsChosenContainer").empty()
        chosenIngredients.map(i =>{
            $("#ingredientsChosenContainer").append(`<span class='ingredients-badge' onclick='handlerChosenIngredient(${i.ingredient_id}, "${i.name}")'>${i.name}</span>`)
        })

    }

    function handlerChosenIngredient(ingredient_id, name){
        let index = chosenIngredients.findIndex(ingredient =>{
            return ingredient.ingredient_id === ingredient_id
        });
        if(index > -1){
            chosenIngredients.splice(index, 1)
        }else{
            chosenIngredients.push({ingredient_id: ingredient_id, name: name})
        }
        printIngredients()
        printChosenIngredients()
    }

    function saveData(){
        let formData = {}
        $(".page-inner form").each(function(){
            $(this).find(':input').not(':input[type=button], :input[type=submit]').each(function (){
                if(!$(this)[0].value){
                    FuxSwalUtility.error("Set an input for " + $(this)[0].name)
                    return 0;
                }
                formData[$(this)[0].name] = $(this)[0].value
            })
        });

        formData["ingredients"] = chosenIngredients.map(ingredient =>{
            return ingredient.ingredient_id
        })

        FuxHTTP.post('<?=routeFullUrl('/user/signup')?>', formData, FuxHTTP.RESOLVE_MESSAGE, FuxHTTP.REJECT_MESSAGE)
            .then(msg =>FuxSwalUtility.success(msg))
            .catch(msg => FuxSwalUtility.error(msg))

    }

</script>

</body>

</html>


