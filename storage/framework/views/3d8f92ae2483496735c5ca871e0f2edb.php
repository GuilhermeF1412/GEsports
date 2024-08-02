<div class="container-fluid topbar-custom d-flex align-items-center justify-content-between">
    <a href="<?php echo e(url('/home')); ?>" class="logo d-flex align-items-center">
        <img src="<?php echo e(asset('logo/logo.svg')); ?>" alt="">
    </a>

    <div class="d-flex align-items-center">
        <!-- Search Bar -->
        <div class="search-bar d-none d-md-flex me-3">
            <form class="search-form d-flex align-items-center" method="POST" action="#">
                <input type="text" name="query" placeholder="Search" title="Enter search keyword">
                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
        </div><!-- End Search Bar -->

        <!-- Profile Dropdown or Login/Signup -->
        <nav class="header-nav">
            <ul class="d-flex align-items-center mb-0">
                <?php if(auth()->guard()->check()): ?>
                    <!-- Authenticated User -->
                    <li class="nav-item dropdown pe-3">
                        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                           data-bs-toggle="dropdown">
                            <img src="<?php echo e(asset('assets/img/profile-img.jpg')); ?>" alt="Profile" class="rounded-circle">
                            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo e(Auth::user()->name); ?></span>
                        </a><!-- End Profile Image Icon -->

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                            <li class="dropdown-header">
                                <h6><?php echo e(Auth::user()->name); ?></h6>
                                <span><?php echo e(Auth::user()->email); ?></span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?php echo e(route('profile')); ?>">
                                    <i class="bi bi-person"></i>
                                    <span>My Profile</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item d-flex align-items-center">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </li>
                        </ul><!-- End Profile Dropdown Items -->
                    </li><!-- End Profile Nav -->
                <?php else: ?>
                    <!-- Guest User -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(route('login')); ?>">Sign In</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav><!-- End Icons Navigation -->
    </div>
</div><!-- End of Topbar -->
<?php /**PATH /var/www/GEsports/resources/views/layouts/topbar.blade.php ENDPATH**/ ?>