<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- SITE TITTLE -->
    <title><?=PROJECT_NAME?> | Your Recipes</title>
    <?= view('website/head') ?>

    <style>
        .card-top-recipes{
            transition: 0.3s;
            cursor: pointer;
        }

        .card-top-recipes:hover{
            margin-top: -50px;
        }
    </style>
</head>

<body class="sign-in-basic">

<!-- Navbar Transparent -->
<?= view('website/navbar')?>
<!-- End Navbar -->

<div class="page-header align-items-start min-vh-100" style="background-image: url('<?=asset('img/userHeaderImg.jpg')?>');" loading="lazy">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container my-auto">
        <div class="row">
            <div class="col-lg-4 col-md-8 col-12 mx-auto">
                <div class="card z-index-0 fadeIn3 fadeInBottom">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                            <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Login</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form role="form" class="text-start">
                            <div class="input-group input-group-outline my-3">
                                <label class="form-label">Username</label>
                                <input type="email" class="form-control">
                            </div>
                            <div class="input-group input-group-outline mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control">
                            </div>
                            <div class="form-check form-switch d-flex align-items-center mb-3">
                                <input class="form-check-input" type="checkbox" id="rememberMe">
                                <label class="form-check-label mb-0 ms-2" for="rememberMe">Remember me</label>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn bg-gradient-primary w-100 my-4 mb-2">Sign in</button>
                            </div>
                            <p class="mt-4 text-sm text-center">
                                Don't have an account?
                                <a href="<?=routeFullUrl('/user/signup')?>" class="text-primary text-gradient font-weight-bold">Sign up</a>
                            </p>
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
                        © <script>
                            document.write(new Date().getFullYear())
                        </script>,
                        made with <i class="fa fa-heart" aria-hidden="true"></i> by Salvathor & Fuso
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                        <li class="nav-item">
                            <a href="<?=routeFullUrl('/')?>" class="nav-link text-white">Homepage</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=routeFullUrl('/user')?>" class="nav-link text-white" target="_blank">About this section</a>
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

</body>

</html>
