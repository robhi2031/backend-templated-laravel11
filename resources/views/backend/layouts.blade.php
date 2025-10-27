<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="robots" content="index, follow, noodp, noydir" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>
        {{ isset($data['title']) ? $data['title'] : 'Unknown' }} - {{ $data['app_name'] }}
    </title>
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
    <link rel="mask-icon" href="{{ asset('/dist/img/favicon/safari-pinned-tab.svg') }}" color="#26296e" />
    <meta name="msapplication-TileColor" content="#061824" />
    <meta name="theme-color" content="#26296e" />
    <meta name="application-name" content="{{ $data['app_name'] }}" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="HandheldFriendly" content="true" />
    @include('backend.partials.styles')
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
	data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true"
	data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
	data-kt-app-sidebar-push-footer="true" class="app-default">
    <!--begin::Theme mode setup on page load-->
    <script>
        window.Laravel = {!! json_encode([
            'pusherKey' => config('broadcasting.connections.pusher.key'),
            'pusherCluster' => config('broadcasting.connections.pusher.options.cluster')
        ]) !!};
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
    <!--begin::App-->
	<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
		<!--begin::Page-->
		<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            @include('backend.partials.header')
			<!--begin::Wrapper-->
			<div class="app-wrapper flex-column flex-row-fluid " id="kt_app_wrapper">
				@include('backend.partials.sidebar')
				<!--begin::Main-->
				<div class="app-main flex-column flex-row-fluid " id="kt_app_main">
					<!--begin::Content wrapper-->
					<div class="d-flex flex-column flex-column-fluid">
						<!--begin::Content-->
						<div id="kt_app_content" class="app-content flex-column-fluid">
							<!--begin::Content container-->
							<div id="kt_app_content_container" class="app-container container-xxl">
                                @yield('content')
							</div>
							<!--end::Content container-->
						</div>
						<!--end::Content-->
					</div>
					<!--end::Content wrapper-->
					@include('backend.partials.footer')
				</div>
				<!--end:::Main-->
			</div>
			<!--end::Wrapper-->
		</div>
		<!--end::Page-->
	</div>
	<!--end::App-->
    @include('backend.partials.engage')
    @include('backend.partials.scripts')
</body>
<!--end::Body-->
</html>
