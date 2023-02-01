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

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h3>Suvrey user id: <?=$surveyRow["survey_user_id"]?></h3>

            <section class="py-3">
                <div class="table-responsive">
                    <div id="watable"></div>
                </div>
            </section>
        </div>
    </div>
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
        url: "<?= routeFullUrl("/dashboard/survey/$surveyRow[survey_user_id]/recipes/watable?accessCode=Y2FjY2FjYWNjYQ==") ?>",
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




