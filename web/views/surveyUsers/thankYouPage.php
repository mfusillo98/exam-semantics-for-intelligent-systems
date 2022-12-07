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
                            <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">End of survey</h4>
                        </div>
                    </div>
                    <div class="card-body page-inner text-center">
                        <h2>Thank you for sharing your opinion!</h2>
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

<!-- Age check -->
<script>
    $("#age-input").on('keyup', function() {
        if ($('#age-input').val() < 18) {
            $('#age-error').removeClass("d-none")
        } else
            $('#age-error').addClass("d-none")
    });
</script>

<script>
    function changeStep(step){
        //Verify fields
        switch (step){
            case 'first-step':
                break
            case 'second-step':
                if ($('#age-input').val() < 18) {return FuxSwalUtility.error("Check age field")}
                if ($('input[name="height"]').val() < 50) {return FuxSwalUtility.error("Check height field")}
                if ($('input[name="weight"]').val() < 30) {return FuxSwalUtility.error("Check weight field")}
                break
            case 'third-step':
                console.log($('input[name="firsts"]').val())
                if (!$('input[name="firsts"]').val()) {return FuxSwalUtility.error("Choose one")}
                break
        }

        let steps = ["first-step", "second-step", "third-step", "fourth-step"]

        for(let i=0; i<steps.length; i++){
            if(step === steps[i]){
                console.log(steps[i])
                $('.'+steps[i]).removeClass(" d-none ")
            }else{
                $('.'+steps[i]).addClass(" d-none ")
            }
        }
    }
</script>

<script>
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

        FuxHTTP.post('<?=routeFullUrl('/survey-users/save')?>', formData, FuxHTTP.RESOLVE_MESSAGE, FuxHTTP.REJECT_MESSAGE)
            .then(msg =>FuxSwalUtility.success(msg))
            .catch(msg => FuxSwalUtility.error(msg))

    }

</script>

</body>

</html>


