<!-- Navbar -->
<nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3  navbar-transparent ">
    <div class="container">
        <a class="navbar-brand text-white " rel="tooltip" title="Designed and Coded by Creative Tim" data-placement="bottom" target="_blank">
            Foot Print
        </a>
        <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon mt-2">
          <span class="navbar-toggler-bar bar1"></span>
          <span class="navbar-toggler-bar bar2"></span>
          <span class="navbar-toggler-bar bar3"></span>
        </span>
        </button>
        <div class="collapse navbar-collapse w-100 pt-3 pb-2 py-lg-0 ms-lg-12 ps-lg-5" id="navigation">
            <ul class="navbar-nav navbar-nav-hover ms-auto">
                <li class="nav-item ms-lg-auto">
                    <a class="nav-link nav-link-icon me-2" href="<?=routeFullUrl('/user/login')?>" target="_blank">
                        Login
                    </a>
                </li>
                <li class="nav-item ms-lg-auto">
                    <a class="nav-link nav-link-icon me-2" href="<?=routeFullUrl('/survey-users')?>" target="_blank">
                        Survey
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
