<?php

?>

<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- SITE TITTLE -->
    <title><?= PROJECT_NAME ?> | Dashboard</title>
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
                        <img class="avatar avatar-xxl position-relative z-index-2" src="<?=asset('img/footPrintLogo.png')?>" alt="bruce" loading="lazy">
                        <h2 class="mt-4">Survey results</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END Testimonials w/ user image & text & info -->

    <!-- START Blogs w/ 4 cards w/ image & text & link -->
    <section class="py-3">
        <div class="table-responsive">
            <div id="watable"></div>
        </div>
    </section>
    <!-- END Blogs w/ 4 cards w/ image & text & link -->
</div>


<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/core/popper.min.js', 'script') ?>
<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/core/bootstrap.min.js', 'script') ?>
<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/plugins/perfect-scrollbar.min.js', 'script') ?>
<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/plugins/parallax.min.js', 'script') ?>
<?= assetOnce('themes/material-kit-2-3.0.0/assets/js/material-kit.min.js?v=3.0.0', 'script') ?>


<?= assetOnce("lib/FuxFramework/FuxSwalUtility.js", "script") ?>
<?= assetOnce('/lib/FuxFramework/FuxUtility.js', "script") ?>
<?= assetOnce('/lib/FuxFramework/FuxHTTP.js', "script") ?>
<?= assetOnce('/lib/watable/jquery.watable.js', "script") ?>

</body>

<!-- Watable -->
<link rel="stylesheet" href="<?= asset("/lib/watable/watable.css")?>">

<script>
    var watable = $('#watable').WATable({
        url: "<?= routeFullUrl("/dashboard/survey/watable?accessCode=Y2FjY2FjYWNjYQ==") ?>",
        pageSize: 20,
        filter: true,
        sorting: true,
        hidePagerOnEmpty: true,
        checkAllToggle: true,
        checkboxes: true,
        actions: {
            filter: true, //Toggle visibility
            columnPicker: true,
        }
    }).data("WATable");


</script>

</html>



