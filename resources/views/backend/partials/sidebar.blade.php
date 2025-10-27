<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar  flex-column " data-kt-drawer="true"
    data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
    data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6 text-center justify-content-center" id="kt_app_sidebar_logo">
        <a href="{{ url('/') }}">
            <svg class="bd-placeholder-img rounded w-50 h-30px app-sidebar-logo-default" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                <rect width="100%" height="100%" fill="#868e96"></rect>
            </svg>
            <svg class="bd-placeholder-img rounded w-100 h-20px app-sidebar-logo-minimize" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                <rect width="100%" height="100%" fill="#868e96"></rect>
            </svg>
        </a>
        <!--begin::Sidebar toggle-->
        <!--begin::Minimized sidebar setup:
            if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") {
                1. "src/js/layout/sidebar.js" adds "sidebar_minimize_state" cookie value to save the sidebar minimize state.
                2. Set data-kt-app-sidebar-minimize="on" attribute for body tag.
                3. Set data-kt-toggle-state="active" attribute to the toggle element with "kt_app_sidebar_toggle" id.
                4. Add "active" class to to sidebar toggle element with "kt_app_sidebar_toggle" id.
            }
        -->
        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-success h-30px w-30px position-absolute top-50 start-100 translate-middle rotate "
            data-kt-toggle="true"
            data-kt-toggle-state="active"
            data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-outline ki-black-left-line fs-3 rotate-180"></i>
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->
    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <div id="kt_app_sidebar_menu_scroll"
                class="scroll-y my-5 mx-3"
                data-kt-scroll="true"
                data-kt-scroll-activate="true"
                data-kt-scroll-height="auto"
                data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
                data-kt-scroll-wrappers="#kt_app_sidebar_menu"
                data-kt-scroll-offset="5px"
                data-kt-scroll-save-state="true">
                <!--begin::Menu-->
                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6"
                    id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                    <div class="menu-item">
                        <a class="menu-link {{ strtolower($activeMenu) == strtolower('DASHBOARD') ? 'active' : '' }}" href="{{ url('/') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-home fs-5"></i>
                            </span>
                            <span class="menu-title fw-normal">Dashboard</span>
                        </a>
                    </div>
                    @php $menus = userMenus(); @endphp
                    @foreach ($menus as $menu)
                        @if (isset($menu['children']) AND $menu['has_child'] == 'Y' )
                            <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ strtolower($activeMenu) == strtolower($menu['menu']) ? 'hover show' : '' }}">
                                <span class="menu-link">
                                    <span class="menu-icon">
                                        <i class="{{ $menu['icon'] }} fs-5"></i>
                                    </span>
                                    <span class="menu-title fw-normal">{{ $menu['menu'] }}</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <div class="menu-sub menu-sub-accordion {{ strtolower($activeMenu) == strtolower($menu['menu']) ? 'show' : '' }}">
                                    @foreach ($menu['children'] as $child)
                                        <div class="menu-item">
                                            <a class="menu-link {{ strtolower($activeMenu) == strtolower($menu['menu']) && strtolower($activeSubMenu) == strtolower($child['menu']) ? 'active' : '' }}"
                                                href="{{ url('/'.$child['route_name']) }}">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title fw-normal">{{ $child['menu'] }}</span>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="menu-item">
                                <a class="menu-link {{ strtolower($activeMenu) == strtolower($menu['menu']) ? 'active' : '' }}"
                                    href="{{ url('/'.$menu['route_name']) }}">
                                    <span class="menu-icon">
                                        <i class="{{ $menu['icon'] }} fs-5"></i>
                                    </span>
                                    <span class="menu-title fw-normal">{{ $menu['menu'] }}</span>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
                <!--end::Menu-->
            </div>
        </div>
    </div>
    <!--end::sidebar menu-->
    <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
        <a href="{{ url('auth/logout') }}"
            class="btn btn-flex flex-center btn-custom btn-primary text-hover-danger overflow-hidden text-nowrap px-0 h-40px w-100">
            <span class="btn-label">
                Sign Out
            </span>
            <i class="bi bi-power btn-icon fs-2 m-0 p-0"></i>
        </a>
    </div>
</div>
<!--end::Sidebar-->
