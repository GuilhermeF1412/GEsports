<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
<header class="topbar-custom">
    <div class="main-container">
        @include('layouts.topbar')
    </div>
</header>

<nav class="subtopbar-custom">
    <div class="main-container">
        @include('layouts.subtopbar')
    </div>
</nav>

<div class="main-container">
    <main>
        @yield('content')
    </main>
</div>

<footer class="footer-custom">
    <div class="container">
        <div class="text-center">
            <div>PROJECT MADE BY GUILHERME FERNANDES</div>
            <div>GESPORTS</div>
        </div>
    </div>
</footer>

<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
<script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('assets/js/main.js') }}"></script>

@stack('script')
</body>
</html>
