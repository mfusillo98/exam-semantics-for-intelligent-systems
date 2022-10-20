<nav class="navbar main-nav navbar-expand-lg p-0">
    <div class="container">
        <a class="navbar-brand" href="<?=routeFullUrl('/')?>"><img src="<?=asset('img/logo.png')?>" height="100"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="ti-menu"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown active dropdown-slide">
                    <a class="nav-link" href="#" data-toggle="dropdown">Home
                        <span><i class="ti-angle-down"></i></span>
                    </a>
                    <!-- Dropdown list -->
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="public/themes/small-apps-premium/index.html">Homepage</a>
                        <!--
                        <a class="dropdown-item" href="public/themes/small-apps-premium/homepage-2.html">Homepage 2</a>
                        <a class="dropdown-item" href="public/themes/small-apps-premium/homepage-3.html">Homepage 3</a>-->
                    </div>
                </li>
                <li class="nav-item dropdown dropdown-slide">
                    <a class="nav-link" href="#" data-toggle="dropdown">Studenti
                        <span><i class="ti-angle-down"></i></span>
                    </a>
                    <!-- Dropdown list -->
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?=routeFullUrl('/students/login')?>">Accedi</a>
                        <a class="dropdown-item"  href="<?=routeFullUrl('/students/registration')?>">Registrati</a>
                    </div>
                </li>
                <li class="nav-item dropdown dropdown-slide">
                    <a class="nav-link" href="#" data-toggle="dropdown">Docenti
                        <span><i class="ti-angle-down"></i></span>
                    </a>
                    <!-- Dropdown list -->
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?=routeFullUrl('/teachers/login')?>">Accedi</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?=routeFullUrl('/about')?>">Chi siamo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?=routeFullUrl('/contact')?>">Contatti</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
