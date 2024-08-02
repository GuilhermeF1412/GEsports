<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $__env->make('layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet">
</head>
<body>
<header class="topbar-custom">
    <div class="main-container">
        <?php echo $__env->make('layouts.topbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</header>

<nav class="subtopbar-custom">
    <div class="main-container">
        <?php echo $__env->make('layouts.subtopbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</nav>

<div class="main-container">
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
</div>

<footer class="footer-custom">
    <div class="main-container">
        <?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</footer>

<!-- Vendor JS Files -->
<script src="<?php echo e(asset('assets/vendor/apexcharts/apexcharts.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendor/chart.js/chart.umd.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendor/echarts/echarts.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendor/quill/quill.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendor/simple-datatables/simple-datatables.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendor/tinymce/tinymce.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendor/php-email-form/validate.js')); ?>"></script>

<!-- Template Main JS File -->
<script src="<?php echo e(asset('assets/js/main.js')); ?>"></script>

<?php echo $__env->yieldPushContent('script'); ?>
</body>
</html>
<?php /**PATH /var/www/GEsports/resources/views/layouts/app.blade.php ENDPATH**/ ?>