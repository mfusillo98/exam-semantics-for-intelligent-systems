<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap 5 -->
<?= assetOnce('bootstrap5/css/bootstrap.css', 'CSS') ?>
<?= assetOnce('bootstrap5/js/bootstrap.bundle.js', 'script') ?>

<!-- Theme -->
<?= assetOnce('themes/material-dashboard/css/material-dashboard.min.css', 'CSS') ?>
<?= assetOnce('themes/material-dashboard/js/material-dashboard.min.js', 'script-defer') ?>
<?= assetOnce('themes/material-dashboard/js/plugins/perfect-scrollbar.min.js', 'script') ?>
<?= assetOnce('themes/material-dashboard/js/plugins/smooth-scrollbar.min.js', 'script') ?>
<?= assetOnce('themes/material-dashboard/js/plugins/world.js', 'script') ?>

<!-- Custom -->
<link rel="stylesheet" href="<?= asset('/css/style.css') ?>">
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

<!-- Fux  -->
<?= assetOnce('lib/FuxFramework/FuxHTTP.js', 'script') ?>
<?= assetOnce('lib/FuxFramework/FuxSwalUtility.js', 'script') ?>
<?= assetOnce('lib/FuxFramework/AsyncCrud.js', 'script') ?>

<!-- SweetAlerts 2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.28.8/dist/sweetalert2.all.min.js"></script>

<!-- Font Awesome 6 -->
<script src="https://kit.fontawesome.com/92b8588aac.js" crossorigin="anonymous"></script>

<!-- Bootstrap select -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/css/bootstrap-select.min.css"
      integrity="sha512-g2SduJKxa4Lbn3GW+Q7rNz+pKP9AWMR++Ta8fgwsZRCUsawjPvF/BxSMkGS61VsR9yinGoEgrHPGPn2mrj8+4w=="
      crossorigin="anonymous" referrerpolicy="no-referrer"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/js/bootstrap-select.min.js"
        integrity="sha512-yrOmjPdp8qH8hgLfWpSFhC/+R9Cj9USL8uJxYIveJZGAiedxyIxwNw4RsLDlcjNlIRR4kkHaDHSmNHAkxFTmgg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/js/i18n/defaults-it_IT.min.js"
        integrity="sha512-tTn7LhB21Y+EuEtsV/XJntUuTUDskZmoZ2khAFdjt3vEyRQj5oy55rPwKldv+8s/chX4evXS/iYsBJJZOV0Vbw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<!-- Tippy.js -->
<?= assetExternalOnce('https://unpkg.com/@popperjs/core@2', 'script'); ?>
<?= assetExternalOnce('https://unpkg.com/tippy.js@6', 'script'); ?>
<?= assetExternalOnce('https://unpkg.com/tippy.js@6/themes/light.css', 'CSS'); ?>

<!-- Watable -->
<link rel="stylesheet" href="<?= asset('lib/watable/watable.css') ?>">
<script src="<?= asset('lib/watable/jquery.watable.js') ?>"></script>