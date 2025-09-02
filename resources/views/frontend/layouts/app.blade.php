<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    <link rel="icon" href="{{ config('settings.site_favicon') ?? asset('favicon.ico') }}" type="image/x-icon">
    @yield('before_vite_build')

    @viteReactRefresh
    @vite(['resources/js/fapp.js', 'resources/css/fapp.css'], 'build')
    @stack('styles')
    @yield('before_head')

    @if (!empty(config('settings.global_custom_css')))
    <style>
        {!! config('settings.global_custom_css') !!}
    </style>
    @endif

    
    
    @php echo ld_apply_filters('web_head', ''); @endphp
</head>

<body
    x-data="{     
        loaded: true,     
    }"     
    class="bg-gray-50"
    style="
        /* background-image: url('https://images.unsplash.com/photo-1583935115064-3785d46de1dc?q=80&w=1974'); */
        background-color: #fff;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
    "
>
    <!-- Preloader -->
    <div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => { setTimeout(() => loaded = false, 1000) })"
        class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent">
        </div>
    </div>
    <!-- End Preloader -->
    <!-- Page Wrapper -->
    
    @include('frontend.layouts.partials.header')

    <!-- Content Area -->        
    @yield('main-content')

    @include('frontend.layouts.partials.footer')

    {!! ld_apply_filters('web_footer_before', '') !!}

    @stack('scripts')


    
    <x-toast-notifications />

    {!! ld_apply_filters('web_footer_after', '') !!}
    <script>
        window.APP_CONFIG = {
            googleMapsKey: "{{ env('YOUR_GOOGLE_MAPS_API_KEY') }}"
        };
    </script>
</body>
</html>
