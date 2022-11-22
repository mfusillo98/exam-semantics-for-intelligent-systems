<?php

/**
 * @var array $user
 */

?>

<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- SITE TITTLE -->
    <title><?= PROJECT_NAME ?> | Your Recipes</title>
    <?= view('website/head') ?>

    <style>
        .card-top-recipes {
            transition: 0.3s;
            cursor: pointer;
        }

        .card-top-recipes:hover {
            margin-top: -50px;
        }
    </style>
</head>

<body class="sign-in-basic">

<!-- Navbar Transparent -->
<?= view('website/navbar') ?>
<!-- End Navbar -->

<!-- -------- START HEADER 4 w/ search book a ticket form ------- -->
<header>
    <div class="page-header min-height-400" style="background-image: url('<?=asset('img/userDashboardCopertina.jpg')?>');"
         loading="lazy">
        <span class="mask bg-gradient-dark opacity-8"></span>
    </div>
</header>
<!-- -------- END HEADER 4 w/ search book a ticket form ------- -->
<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6 mb-4">
    <!-- START Testimonials w/ user image & text & info -->
    <section class="py-sm-7 py-5 position-relative">
        <div class="container">
            <div class="row">
                <div class="col-12 mx-auto">
                    <div class="mt-n8 mt-md-n9 text-center">
                        <img class="avatar avatar-xxl shadow-xl position-relative z-index-2"
                             src="<?=asset('img/userProfileImg.png')?>" alt="bruce" loading="lazy">
                    </div>
                    <div class="row py-5">
                        <div class="col-lg-7 col-md-7 z-index-2 position-relative px-md-2 px-sm-5 mx-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h3 class="mb-0"><?=$user["username"]?></h3>
                            </div>
                            <div class="row mb-4">
                                <div class="col-auto">
                                    <span class="h6"><?=$user["age"]?></span>
                                    <span>Age</span>
                                </div>
                                <div class="col-auto">
                                    <span class="h6"><?=$user["height"]?> cm</span>
                                    <span>Height</span>
                                </div>
                                <div class="col-auto">
                                    <span class="h6"><?=$user["weight"]?> kg</span>
                                    <span>Weight</span>
                                </div>
                            </div>
                            <p class="text-lg mb-0">
                                Decisions: If you can’t decide, the answer is no.
                                If two equally difficult paths, choose the one more
                                painful in the short term (pain avoidance is creating
                                an illusion of equality). Choose the path that leaves
                                you more equanimous. <br><a href="javascript:;" class="text-info icon-move-right">More
                                    about me
                                    <i class="fas fa-arrow-right text-sm ms-1"></i>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END Testimonials w/ user image & text & info -->

    <!-- START Blogs w/ 4 cards w/ image & text & link -->
    <section class="py-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-5">
                    <h3>Your km 0 foods</h3>
                    <span>Here you can find your km0 foods that you have chosen during registration process</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9 col-sm-6">
                    <?php
                        foreach ($user["km0_ingredients"] as $i){
                            echo "<div class='btn btn-primary mx-3'>$i[name]</div>";
                        }
                    ?>
                </div>
                <div class="col-lg-3 col-md-12 col-12">
                    <div class="card card-blog card-background cursor-pointer">
                        <div class="full-background" style="background-image: url('../assets/img/examples/blog2.jpg')"
                             loading="lazy"></div>
                        <div class="card-body">
                            <div class="content-left text-start my-auto py-4">
                                <h2 class="card-title text-white">Flexible work hours</h2>
                                <p class="card-description text-white">Rather than worrying about switching offices
                                    every couple years, you stay in the same place.</p>
                                <a href="javascript:;" class="text-white text-sm icon-move-right">Read More
                                    <i class="fas fa-arrow-right text-xs ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END Blogs w/ 4 cards w/ image & text & link -->

    <!--Search section -->
    <?=view('website/recipesSearchSection', ["useCfi" => 1])?>
</div>


<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/core/popper.min.js', 'script') ?>
<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/core/bootstrap.min.js', 'script') ?>
<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/plugins/perfect-scrollbar.min.js', 'script') ?>
<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/plugins/parallax.min.js', 'script') ?>
<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/material-kit.min.js?v=3.0.0', 'script') ?>


<?= assetOnce("lib/FuxFramework/FuxSwalUtility.js", "script") ?>
<?= assetOnce('/lib/FuxFramework/FuxUtility.js', "script") ?>
<?= assetOnce('/lib/FuxFramework/FuxHTTP.js', "script") ?>

</body>

<script>
    (function () {
        document.querySelector('.container form').addEventListener('submit', handleLogin);

        function handleLogin(e) {
            e.preventDefault();
            FuxSwalUtility.loading('Attendere prego...');
            FuxHTTP.post('<?= routeFullUrl('/user/login') ?>', {
                username: e.target.querySelector('[name="username"]').value,
                password: e.target.querySelector('[type="password"]').value,
            }, FuxHTTP.RESOLVE_DATA, FuxHTTP.REJECT_MESSAGE)
                .then(redirectUrl => {
                    window.location.href = redirectUrl;
                })
                .catch();
        }

    })();
</script>

</html>


