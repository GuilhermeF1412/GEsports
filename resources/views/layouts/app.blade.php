<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body class="page-container">
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

    <div class="main-container content-wrap">
        <main>
            @yield('content')
        </main>
    </div>

    @include('layouts.footer')

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    @stack('script')
</body>
</html>
