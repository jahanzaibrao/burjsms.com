<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php the_title(); ?></title>
    <link href="<?php echo get_template_directory_uri(); ?>/css/styles.css" rel="stylesheet" />
    <link href="<?php echo get_template_directory_uri(); ?>/css/prism.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/assets/img/favicon.png" />
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="layoutDefault">
        <div id="layoutDefault_content">
            <main>
                <!-- start menu-->
                <nav class="navbar navbar-marketing navbar-expand-lg bg-transparent navbar-dark fixed-top">
                    <div class="container">
                        <a class="navbar-brand text-white" href="/">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/sms_handover_logo.png" /> </a><button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i data-feather="menu"></i>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <?php //wp_nav_menu(array('menu_class' => 'navbar-nav ml-auto mr-lg-5', 'container' => 'ul',)); 
                            ?>
                            <ul class="navbar-nav ml-auto mr-lg-5">
                                <li class="nav-item">
                                    <a class="nav-link" href="/">Home </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/about-us">About Us </a>
                                </li>
								
                                <li class="nav-item dropdown dropdown-xl no-caret">
                                    <a class="nav-link dropdown-toggle" id="navbarDropdownDemos" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Products<i class="fas fa-chevron-right dropdown-arrow"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right animated--fade-in-up mr-lg-n15" aria-labelledby="navbarDropdownDemos">
                                        <div class="row no-gutters">
                                            <div class="col-lg-5 p-lg-3 bg-img-cover overlay overlay-primary overlay-70 d-none d-lg-block" style="background-image: url('https://source.unsplash.com/mqO0Rf-PUMs/500x350');">
                                                <div class="d-flex h-100 w-100 align-items-center justify-content-center">
                                                    <div class="text-white text-center z-1">
                                                        <div class="mb-3">
                                                            Our clients across all industries enjoy premium
                                                            enterprise-grade SMS services.
                                                        </div>
                                                        <a class="btn btn-white btn-sm text-primary rounded-pill" href="/industries">View All Industries</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-7 p-lg-5">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <h6 class="dropdown-header text-primary">
                                                            Messaging
                                                        </h6>
                                                        <a class="dropdown-item" href="/bulk-sms">Bulk SMS</a><a class="dropdown-item" href="/bulk-sms-reports">Reports &amp; Statistics</a>
                                                        <a class="dropdown-item" href="/bulk-sms-phonebook">Dynamic Phonebook</a>
                                                        <a class="dropdown-item" href="/click-tracking">Click Tracking</a>
                                                        <div class="dropdown-divider border-0"></div>
                                                        <h6 class="dropdown-header text-primary">
                                                            2-Way SMS
                                                        </h6>
                                                        <a class="dropdown-item" href="/virtual-mobile-numbers">Shortcode/Longcode</a><a class="dropdown-item" href="/mcs-subscriber">Missed-call Subscriber</a>

                                                        <div class="dropdown-divider border-0"></div>
                                                        <h6 class="dropdown-header text-primary">HLR</h6>
                                                        <a class="dropdown-item" href="/hlr-lookup">Lookup Service</a>
                                                        <div class="dropdown-divider border-0 d-lg-none"></div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <h6 class="dropdown-header text-primary">
                                                            Reseller Platform
                                                        </h6>
                                                        <a class="dropdown-item" href="/white-label">White Label Panel</a><a class="dropdown-item" href="/lead-capture">Lead Capture</a>
                                                        <div class="dropdown-divider border-0"></div>
                                                        <h6 class="dropdown-header text-primary">API</h6>
                                                        <a class="dropdown-item" href="/sms-api">HTTP/XML API</a><a class="dropdown-item" href="/smpp-api">SMPP API</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/pricing"> Pricing </a>
                                </li>
                                <li class="nav-item dropdown no-caret">
                                    <a class="nav-link dropdown-toggle" id="navbarDropdownDocs" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Developers<i class="fas fa-chevron-right dropdown-arrow"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right animated--fade-in-up" aria-labelledby="navbarDropdownDocs">
                                        <a class="dropdown-item py-3" href="/developer-docs">
                                            <div class="icon-stack bg-primary-soft text-primary mr-4">
                                                <i class="fas fa-book-open"></i>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500">Documentation</div>
                                                Usage instructions and reference
                                            </div>
                                        </a>
                                        <div class="dropdown-divider m-0"></div>
                                        <a class="dropdown-item py-3" href="/api-status">
                                            <div class="icon-stack bg-primary-soft text-primary mr-4">
                                                <i class="fas fa-code"></i>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500">API Status</div>
                                                Real-time status of our API services
                                            </div>
                                        </a>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/contact"> Contact </a>
                                </li>
                            </ul>
                            <a class="btn-teal btn rounded-pill px-4 ml-lg-4" href="https://console.smshandover.com/">Login / Signup</a>
                        </div>
                    </div>
                </nav>
                <!-- end menu-->