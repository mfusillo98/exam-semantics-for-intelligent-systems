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
                <li class="nav-item dropdown dropdown-hover mx-2 ms-lg-6">
                    <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center" id="dropdownMenuPages8" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="material-icons opacity-6 me-2 text-md">dashboard</i>
                        Website
                        <img src="<?=asset('themes/material-kit-2-3.0.0/assets/img/down-arrow-white.svg')?>" alt="down-arrow" class="arrow ms-2 d-lg-block d-none">
                        <img src="<?=asset('themes/material-kit-2-3.0.0/assets/img/down-arrow-dark.svg')?>" alt="down-arrow" class="arrow ms-2 d-lg-none d-block">
                    </a>
                    <div class="dropdown-menu dropdown-menu-animation ms-n3 dropdown-md p-3 border-radius-lg mt-0 mt-lg-3" aria-labelledby="dropdownMenuPages8">
                        <div class="d-none d-lg-block">
                            <h6 class="dropdown-header text-dark font-weight-bolder d-flex align-items-center px-1">
                                Landing Pages
                            </h6>
                            <a href="../pages/about-us.html" class="dropdown-item border-radius-md">
                                <span>About Us</span>
                            </a>
                            <a href="../pages/contact-us.html" class="dropdown-item border-radius-md">
                                <span>Contact Us</span>
                            </a>
                            <a href="../pages/author.html" class="dropdown-item border-radius-md">
                                <span>Author</span>
                            </a>
                            <h6 class="dropdown-header text-dark font-weight-bolder d-flex align-items-center px-1 mt-3">
                                Account
                            </h6>
                            <a href="<?=routeFullUrl('/user/login')?>" class="dropdown-item border-radius-md">
                                <span>Log In</span>
                            </a>
                        </div>
                        <div class="d-lg-none">
                            <h6 class="dropdown-header text-dark font-weight-bolder d-flex align-items-center px-1">
                                Landing Pages
                            </h6>
                            <a href="../pages/about-us.html" class="dropdown-item border-radius-md">
                                <span>About Us</span>
                            </a>
                            <a href="../pages/contact-us.html" class="dropdown-item border-radius-md">
                                <span>Contact Us</span>
                            </a>
                            <a href="../pages/author.html" class="dropdown-item border-radius-md">
                                <span>Author</span>
                            </a>
                            <h6 class="dropdown-header text-dark font-weight-bolder d-flex align-items-center px-1 mt-3">
                                Account
                            </h6>
                            <a href="../pages/sign-in.html" class="dropdown-item border-radius-md">
                                <span>Sign In</span>
                            </a>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown dropdown-hover mx-2">
                    <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center" id="dropdownMenuDocs" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="material-icons opacity-6 me-2 text-md">article</i>
                        Docs
                        <img src="<?=asset('themes/material-kit-2-3.0.0/assets/img/down-arrow-white.svg')?>" alt="down-arrow" class="arrow ms-2 d-lg-block d-none">
                        <img src="<?=asset('themes/material-kit-2-3.0.0/assets/img/down-arrow-dark.svg')?>" alt="down-arrow" class="arrow ms-2 d-lg-none d-block">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-animation dropdown-menu-end dropdown-md dropdown-md-responsive mt-0 mt-lg-3 p-3 border-radius-lg" aria-labelledby="dropdownMenuDocs">
                        <div class="d-none d-lg-block">
                            <ul class="list-group">
                                <li class="nav-item list-group-item border-0 p-0">
                                    <a class="dropdown-item py-2 ps-3 border-radius-md" href=" https://www.creative-tim.com/learning-lab/bootstrap/overview/material-kit ">
                                        <h6 class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">Getting Started</h6>
                                        <span class="text-sm">All about overview, quick start, license and contents</span>
                                    </a>
                                </li>
                                <li class="nav-item list-group-item border-0 p-0">
                                    <a class="dropdown-item py-2 ps-3 border-radius-md" href=" https://www.creative-tim.com/learning-lab/bootstrap/colors/material-kit ">
                                        <h6 class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">Foundation</h6>
                                        <span class="text-sm">See our colors, icons and typography</span>
                                    </a>
                                </li>
                                <li class="nav-item list-group-item border-0 p-0">
                                    <a class="dropdown-item py-2 ps-3 border-radius-md" href=" https://www.creative-tim.com/learning-lab/bootstrap/alerts/material-kit ">
                                        <h6 class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">Components</h6>
                                        <span class="text-sm">Explore our collection of fully designed components</span>
                                    </a>
                                </li>
                                <li class="nav-item list-group-item border-0 p-0">
                                    <a class="dropdown-item py-2 ps-3 border-radius-md" href=" https://www.creative-tim.com/learning-lab/bootstrap/datepicker/material-kit ">
                                        <h6 class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">Plugins</h6>
                                        <span class="text-sm">Check how you can integrate our plugins</span>
                                    </a>
                                </li>
                                <li class="nav-item list-group-item border-0 p-0">
                                    <a class="dropdown-item py-2 ps-3 border-radius-md" href=" https://www.creative-tim.com/learning-lab/bootstrap/utilities/material-kit ">
                                        <h6 class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">Utility Classes</h6>
                                        <span class="text-sm">For those who want flexibility, use our utility classes</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="row d-lg-none">
                            <div class="col-md-12 g-0">
                                <a class="dropdown-item py-2 ps-3 border-radius-md" href="../pages/about-us.html">
                                    <h6 class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">Getting Started</h6>
                                    <span class="text-sm">All about overview, quick start, license and contents</span>
                                </a>
                                <a class="dropdown-item py-2 ps-3 border-radius-md" href="../pages/about-us.html">
                                    <h6 class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">Foundation</h6>
                                    <span class="text-sm">See our colors, icons and typography</span>
                                </a>
                                <a class="dropdown-item py-2 ps-3 border-radius-md" href="../pages/about-us.html">
                                    <h6 class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">Components</h6>
                                    <span class="text-sm">Explore our collection of fully designed components</span>
                                </a>
                                <a class="dropdown-item py-2 ps-3 border-radius-md" href="../pages/about-us.html">
                                    <h6 class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">Plugins</h6>
                                    <span class="text-sm">Check how you can integrate our plugins</span>
                                </a>
                                <a class="dropdown-item py-2 ps-3 border-radius-md" href="../pages/about-us.html">
                                    <h6 class="dropdown-header text-dark font-weight-bolder d-flex justify-content-cente align-items-center p-0">Utility Classes</h6>
                                    <span class="text-sm">For those who want flexibility, use our utility classes</span>
                                </a>
                            </div>
                        </div>
                    </ul>
                </li>
                <li class="nav-item ms-lg-auto">
                    <a class="nav-link nav-link-icon me-2" href="https://github.com/creativetimofficial/soft-ui-design-system" target="_blank">
                        <i class="fa fa-github me-1"></i>
                        <p class="d-inline text-sm z-index-1 font-weight-bold" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Star us on Github">Github</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
