<!-- PALETTE DI RIFERIMENTO -->
<!-- https://colorhunt.co/palette/3038413a475000adb5eeeeee-->

<!DOCTYPE html>
<html lang="ita">
<head>
    <!-- SITE TITTLE -->
    <title><?=PROJECT_NAME?></title>
    <?= view('website/head') ?>
</head>

<body class="body-wrapper" data-spy="scroll" data-target=".privacy-nav">

<?= view('website/navbar')?>

<!--================================
=            Page Title            =
=================================-->

<section class="section page-title">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 m-auto">
                <!-- Page Title -->
                <h1>Contattaci</h1>
                <!-- Page Description -->
                <p>Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Cras ultricies ligula sed magna dictum porta.</p>
            </div>
        </div>
    </div>
</section>

<!--====  End of Page Title  ====-->


<!--=====================================
=            Address and Map            =
======================================-->

<section class="address">
    <div class="container">
        <div class="row">
            <div class="col-md-6 align-self-center">
                <div class="block">
                    <div class="address-block text-center">
                        <div class="icon">
                            <i class="ti-mobile"></i>
                        </div>
                        <div class="details">
                            <h3>(39) 789 456 7890 (ITA)</h3>
                            <h3>(39) 016 725 0455 (ITA)</h3>
                        </div>
                    </div>
                    <div class="address-block text-center">
                        <div class="icon">
                            <i class="ti-map-alt"></i>
                        </div>
                        <div class="details">
                            <h3>Via Mario Rossi libero</h3>
                            <h3>123, Roma, ITA</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="google-map">
                    <!-- Google Map -->
                    <div id="map_canvas" data-latitude="51.507351" data-longitude="-0.127758"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!--====  End of Address and Map  ====-->

<section class="contact-form section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Inviaci una mail</h2>
            </div>
            <div class="col-12">
                <form action="">
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6">
                            <input class="form-control main" type="text" placeholder="Name" required>
                        </div>
                        <!-- Email -->
                        <div class="col-md-6">
                            <input class="form-control main" type="email" placeholder="Il tuo indirizzo" required>
                        </div>
                        <!-- subject -->
                        <div class="col-md-12">
                            <input class="form-control main" type="text" placeholder="Oggetto" required>
                        </div>
                        <!-- Message -->
                        <div class="col-md-12">
                            <textarea class="form-control main" name="message" rows="10" placeholder="Il tuo messaggio"></textarea>
                        </div>
                        <!-- Submit Button -->
                        <div class="col-12 text-right">
                            <button class="btn btn-main-md">Invia :)</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!--============================
=            Footer            =
=============================-->

<?= view("website/footer")?>


<!-- JAVASCRIPTS -->
<script src="public/themes/small-apps-premium/plugins/jquery/jquery.js"></script>
<script src="public/themes/small-apps-premium/plugins/popper/popper.min.js"></script>
<script src="public/themes/small-apps-premium/plugins/bootstrap/bootstrap.min.js"></script>
<script src="public/themes/small-apps-premium/plugins/owl-carousel/owl.carousel.min.js"></script>
<script src="public/themes/small-apps-premium/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="public/themes/small-apps-premium/plugins/syotimer/jquery.syotimer.min.js"></script>
<script src="public/themes/small-apps-premium/plugins/aos/aos.js"></script>
<!-- google map -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgeuuDfRlweIs7D6uo4wdIHVvJ0LonQ6g"></script>
<script src="public/themes/small-apps-premium/plugins/google-map/gmap.js"></script>

<script src="public/themes/small-apps-premium/js/custom.js"></script>
</body>

</html>

