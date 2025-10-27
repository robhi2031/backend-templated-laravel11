<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!--begin::Head-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="robots" content="index, follow, noodp, noydir" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>
        {{ isset($data['title']) ? $data['title'] : 'Unknown' }} - {{ $data['app_name'] }}
    </title>
    <meta name="description" content="{{ $data['desc'] }}" />
    <meta name="keywords" content="{{ $data['keywords'] }}" />
    <meta name="author" content="@RobhiTranzad" />
    <meta name="email" content="robhi.sanjaya@gmail.com" />
    <meta name="website" content="{{ $data['url'] }}" />
    <meta name="Version" content="{{ $data['app_version'] }}" />
    <meta name="docsearch:language" content="id">
    <meta name="docsearch:version" content="{{ $data['app_version'] }}" />
    <link rel="canonical" href="{{ $data['url'] }}" />
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/dist/img/favicon/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/dist/img/favicon/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/dist/img/favicon/favicon-16x16.png') }}" />
    <link rel="manifest" href="{{ asset('/dist/img/favicon/site.webmanifest') }}" />
    <link rel="mask-icon" href="{{ asset('/dist/img/favicon/safari-pinned-tab.svg') }}" color="#002611" />
    <meta name="msapplication-TileColor" content="#002611" />
    <meta name="theme-color" content="#00451F" />
    <meta name="application-name" content="{{ $data['app_name'] }}" />
    <meta name="msapplication-TileImage" content="{{ $data['thumb'] }}" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="HandheldFriendly" content="true" />
    <!-- Twitter -->
    <meta name="twitter:widgets:csp" content="on" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:url" content="{{ $data['url'] }}" />
    <meta name="twitter:site" content="{{ $data['app_name'] }}" />
    <meta name="twitter:creator" content="@robhitranzad" />
    <meta name="twitter:title" content="{{ $data['title'] }}" />
    <meta name="twitter:description" content="{{ $data['desc'] }}" />
    <meta name="twitter:image" content="{{ $data['thumb'] }}" />
    <!-- Facebook -->
    <meta property="og:locale" content="id_ID" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ $data['url'] }}" />
    <meta property="og:title" content="{{ $data['title'] }}" />
    <meta property="og:description" content="{{ $data['desc'] }}" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="{{ $data['thumb'] }}" />
    <meta property="og:image:type" content="image/png" />
    <meta property="og:image:width" content="1000" />
    <meta property="og:image:height" content="500" />
    @include('auth.partials.styles')
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center"
    style="background-size: cover;
        background-position: unset;
        background: rgba(3, 55, 34, 0.75);
        background: radial-gradient(linear-gradient(0deg, rgb(2 18 13), #060606cc));
        background: -moz-radial-gradient(linear-gradient(0deg, rgb(2 18 13), #060606cc));
        background: -webkit-radial-gradient(linear-gradient(0deg, rgb(2 18 13), #060606cc));
        filter: progid: DXImageTransform.Microsoft.gradient( startColorstr="#054A2E", endColorstr="#0b2000", GradientType=1 );
    ">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        @yield('content')
        @include('auth.partials.footer')
    </div>
    <!--end::Root-->
    @include('auth.partials.scripts')
</body>
<!--end::Body-->
</html>
