@extends('auth.layouts')
@section('content')
<!--begin::Authentication - Sign-in -->
<div class="d-flex flex-column flex-center flex-column-fluid">
    <div class="d-flex flex-column flex-center text-center p-0 p-md-10" id="kt_sign_in">
        <!--begin::Wrapper-->
        <div class="bg-body shadow-xs d-flex flex-column flex-center rounded-4 w-md-450px p-10">
            <!--begin::Content-->
            <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-350px">
                <!--begin::Wrapper-->
                <div class="d-flex flex-center flex-column flex-column-fluid pb-5 pb-lg-10">
                    <!--begin::Form-->
                    <form class="form w-100" id="kt_sign_in_form">
                        <!--begin::Img Logo-->
                        <div class="text-center mb-5" id="hLogo-login">
                            <h1 class="placeholder-glow text-center">
                                <p class="placeholder rounded w-75 h-50px"></p>
                            </h1>
                        </div>
                        <!--end::Img Logo-->
                        <!--begin::Submit SignIn 1-->
                        <div id="fBody-login1">
                            <div class="text-center mb-10">
                                <h1 class="text-gray-900 fw-bolder mb-0">Login</h1>
                                <div class="text-gray-500 fw-semibold fs-7">Use your user account</div>
                            </div>
                            <div class="form-floating fv-row mb-8">
                                <input type="text" class="form-control" name="username" id="username" autocomplete="off" placeholder="Username atau Email" />
                                <label for="username">Username atau Email</label>
                            </div>
                            <div class="d-grid mb-5">
                                <button type="button" id="btn-login1" class="btn btn-sm btn-success">
                                    <span class="indicator-label">
                                        <i class="ki-outline ki-entrance-left fs-4"></i> Berikutnya
                                    </span>
                                    <span class="indicator-progress">
                                        Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                            <!-- <div class="d-flex flex-stack">
                                <a href="{{ url('/') }}" class="link-danger text-hover-danger fs-6 fw-bolder m-3"><i class="ki-outline ki-home text-danger"></i> Halaman Depan</a>
                                <button type="button" id="btn-login1" class="btn btn-sm btn-success m-3">
                                    <span class="indicator-label">
                                        <i class="ki-outline ki-entrance-left fs-4"></i> Berikutnya
                                    </span>
                                    <span class="indicator-progress">
                                        Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div> -->
                        </div>
                        <!--end::Submit SignIn 1-->
                        <!--begin::Submit SignIn 2-->
                        <div id="fBody-login2" style="display: none;">
                            <!--begin::Heading-->
                            <div class="text-center mb-10" id="hT-login2">
                                <h1 class="placeholder-glow text-center">
                                    <p class="placeholder rounded w-50 h-25px mb-3"></p>
                                    <p class="placeholder rounded w-75 h-35px"></p>
                                </h1>
                            </div>
                            <!--begin::Heading-->
                            <input type="hidden" name="hideMail" />
                            <!--begin::Input group-->
                            <div class="form-floating fv-row mb-3">
                                <input type="password" class="form-control" name="password" id="password" autocomplete="off" placeholder="Masukkan password anda" />
                                <label for="password">Masukkan password anda</label>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Wrapper | flex-stack-->
                            <div class="d-flex justify-content-end mb-5">
                                <div class="form-check form-check-custom form-check-solid form-check-sm form-check-success">
                                    <input class="form-check-input" type="checkbox" name="showPass_checkbox" id="showPass_checkbox" />
                                    <label class="form-check-label" for="showPass_checkbox">Tampilkan password</label>
                                </div>
                            </div>
                            <!--end::Wrapper-->
                            <!--begin::Actions-->
                            <div class="d-flex flex-stack">
                                <!--begin::Link-->
                                <a href="javascript:void(0);" class="link-danger text-hover-danger fs-6 fw-bolder m-3">Lupa Password ?</a>
                                <!--end::Link-->
                                <!--begin::Submit button-->
                                <button type="button" id="btn-login2" class="btn btn-sm btn-success m-3">
                                    <span class="indicator-label">
                                        <i class="bi bi-box-arrow-in-right fs-4"></i> Login
                                    </span>
                                    <span class="indicator-progress">
                                        Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                                <!--end::Submit button <span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...-->
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Submit SignIn 2-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Wrapper-->
    </div>
</div>
<!--end::Authentication - Sign-in-->
@endsection
