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
            margin: 5px 5px 5px 5px;
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
     style="background-image: url('<?= asset('img/userHeaderImg.jpg') ?>');" loading="lazy">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container my-auto">
        <div class="row">
            <div class="col-md-8 col-12 mx-auto">
                <div class="card z-index-0 fadeIn3 fadeInBottom">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                            <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Sign up</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form role="form" class="text-start">
                            <div class="first-step">
                                <div class="input-group input-group-outline">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" name="username">
                                </div>
                                <div class="input-group input-group-outline mt-3 mb-2">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="input-group input-group-outline mt-2">
                                    <label class="form-label">Conferma password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                </div>
                                <div class="mb-3"><small id="password_message"></small></div>
                                <div class="row mt-3">
                                    <div class="col-6 pr-2">
                                        <div class="input-group input-group-outline">
                                            <label class="form-label">Age</label>
                                            <input type="number" class="form-control" name="age">
                                        </div>
                                    </div>
                                    <div class="col-6 pr-2">
                                        <div class="input-group input-group-outline">
                                            <select class="form-control" name="gender" required>
                                                <option value="">Choose your gender</option>
                                                <option value="m">Male</option>
                                                <option value="f">Female</option>
                                                <option value="o">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6 pr-2">
                                        <div class="input-group input-group-outline">
                                            <label class="form-label">Height (cm)</label>
                                            <input type="number" class="form-control" name="height">
                                        </div>
                                    </div>
                                    <div class="col-6 pr-2">
                                        <div class="input-group input-group-outline">
                                            <label class="form-label">Weight (kg)</label>
                                            <input type="number" class="form-control" name="weight">
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

                                <!-- before research -->
                                <div class="col-12 mt-4" id="before-search-container">
                                    <div class="card card-body shadow-sm text-center">
                                        <img src="<?= asset('img/searchIngredientsBlankImg.svg') ?>" width="250" style="margin: auto">
                                        <span class="text-muted mt-2">Here you will see results...</span>
                                    </div>
                                </div>

                                <!-- loader container -->
                                <div class="col-12 mt-4 loader d-none" id="loader-container"></div>

                                <!-- Ingredients container -->
                                <div class="my-2" id="ingredientsContainer"></div>

                                <div class="text-center">
                                    <button type="button" onclick="changeStep(2)" class="btn bg-gradient-primary w-100 my-4 mb-2">Submit</button>
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

    //Gestisce il timeout della ricerca
    $('#ingredientsBar').on('keyup', function () {
        clearTimeout(typingTimer);
        if (this.value.length > 1) {
            typingTimer = setTimeout(_ => {
                showBeforeSearchContainer(false)
                getIngredients(this.value)
            }, doneTypingTimer);
        }else {
            let container = document.getElementById('ingredientsContainer')
            container.innerHTML = "";
            showBeforeSearchContainer(true)
        }
    });


    function getIngredients(query){
        FuxHTTP.get('<?=routeFullUrl('/user/signup/get-ingredients')?>', {query: query}, FuxHTTP.RESOLVE_DATA, FuxHTTP.REJECT_MESSAGE)
        .then(data =>{
            console.log(data)
            data.ingredients.map(d =>{
                $("#ingredientsContainer").append(`<span class='ingredients-badge'>${d.name}</span>`)
            })
        })
    }

    function showBeforeSearchContainer(show){
        if(show){
            $("#before-search-container").removeClass("d-none")
        }else {
            $("#before-search-container").addClass("d-none")
        }
    }



</script>

</body>

</html>

