<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FUTA Bus Demo')</title>
    <!-- Đường dẫn tới base.css -->
    <link rel="stylesheet" href="{{ asset('assets/css/Index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/Header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
    <!-- Swiper carousel CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @yield('styles')
    @stack('styles')
</head>

<body>
    @include('layouts.header')

    <main>
        @yield('content')
    </main>

    @include('home.chat')
    @include('layouts.footer')

    <!-- FUTA Chat Widget -->
    <script src="{{ asset('assets/js/futa-chat.js') }}?v={{ time() }}"></script>

    <!-- Swiper JS (available for pages that initialize Swiper) -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

    @yield('scripts')
</body>

</html>