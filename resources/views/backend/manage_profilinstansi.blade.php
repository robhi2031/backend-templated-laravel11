@extends('backend.layouts', ['activeMenu' => 'Kelola Aplikasi', 'activeSubMenu' => 'Profil Instansi'])
@section('content')
<div class="row g-7">
    <div class="col-lg-12">
        <!--begin::Profil Instansi-->
        <div class="card mb-5 mb-xl-10" id="cardProfilInstansi">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-5">
                    <h3 class="fw-bolder m-0 mb-3"><i class="las la-pen text-gray-900 fs-2 me-3"></i>Edit Profil Instansi</h3>
                    <a href="javascript:history.back();" class="btn btn-sm btn-bg-light btn-color-danger btn-active-light-danger"><i class="las la-undo fs-3 me-1"></i>Kembali</a>
                </div>
                <!--begin:::Tabs-->
                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x fs-6 fw-semibold mb-8 gap-2" id="tab-settings-profil">
                    <li class="nav-item">
                        <a class="nav-link nav-link-hover-success text-hover-success text-active-success d-flex align-items-center pb-4 active" data-bs-toggle="tab" href="#tab_general">
                            <i class="ki-outline ki-notepad-edit fs-4 me-1"></i>General
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-hover-success text-hover-success text-active-success d-flex align-items-center pb-4" data-bs-toggle="tab" href="#tab_logo">
                            <i class="ki-outline ki-bucket-square fs-4 me-1"></i>Logo & Lainnya
                        </a>
                    </li>
                </ul>
                <!--end:::Tabs-->
                <!--begin::Tab content-->
                <div class="tab-content">
                    <!--begin:::General-->
                    <div class="tab-pane fade show active" id="tab_general" role="tabpanel">
                        <!--begin::Form-->
                        <form id="form-general" class="form" onsubmit="return false">
                            <input type="hidden" name="roleActive" value="{{ auth()->user()->getRoleNames()[0] }}" />
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="name">Nama</label>
                                <div class="col-lg-8">
                                    <input type="text" name="name" id="name" class="form-control form-control-sm form-control-solid mb-3 mb-lg-0" maxlength="255" placeholder="Isikan nama instansi ..." />
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="short_description">Deskripsi</label>
                                <div class="col-lg-8">
                                    <textarea name="short_description" id="short_description" class="form-control form-control-sm form-control-solid mb-3 mb-lg-0" rows="2" maxlength="255" placeholder="Isikan deskripsi singkat instansi ..."></textarea>
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="address">Pin Alamat Kantor</label>
                                <div class="col-lg-8">
                                    <input type="hidden" id="latitudeAddress" name="latitudeAddress" />
                                    <input type="hidden" id="longitudeAddress" name="longitudeAddress" />
                                    <!--begin::Input group-->
                                    <div class="input-group input-group-sm input-group-solid mb-0">
                                        <span class="input-group-text"><i class="bi bi-pin-map-fill"></i></span>
                                        <input type="text" class="form-control form-control-solid" id="searchMapAddress" name="searchMapAddress" placeholder="Cari lokasi alamat kantor ..." />
                                        <span class="input-group-text border-left-0 cursor-pointer text-hover-success" id="btn-searchAddress">
                                            <i class="las la-search fs-3"></i>
                                        </span>
                                    </div>
                                    <!--end::Input group-->
                                    <div class="rounded bg-secondary" id="mapAddress" style="width: 100%; height: 350px;"></div>
                                    <!--end::Input group-->
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="address">Alamat Kantor</label>
                                <div class="col-lg-8">
                                    <textarea name="address" id="address" class="form-control form-control-sm form-control-solid" rows="2" maxlength="225" placeholder="Isikan alamat Kantor ..."></textarea>
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="phone_number">No. Telpon/ Hp Kantor</label>
                                <div class="col-lg-8">
                                    <div class="input-group input-group-sm input-group-solid mb-2">
                                        <span class="input-group-text"><i class="las la-phone"></i></span>
                                        <input type="text" class="form-control form-control-sm form-control-solid no-space form-enter" name="phone_number" id="phone_number" placeholder="Isikan No. Telpon/ Hp Kantor ..." />
                                    </div>
                                    <div class="form-text">*) Pastikan No. Telpon/ Hp Kantor sesuai format dan masih aktif digunakan, contoh: <code>6283122222222</code></div>
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="email">Email Kantor</label>
                                <div class="col-lg-8">
                                    <div class="input-group input-group-sm input-group-solid mb-2">
                                        <span class="input-group-text"><i class="las la-phone"></i></span>
                                        <input type="text" class="form-control form-control-sm form-control-solid no-space form-enter" name="email" id="email" maxlength="225" placeholder="Isikan Email Kantor ..." />
                                    </div>
                                    <div class="form-text">*) Pastikan Email Kantor sesuai format dan masih aktif digunakan, contoh: <code>sample.office@gmail.com</code></div>
                                </div>
                            </div>
                            <!--end::Input group-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end:::General-->
                    <!--begin:::Logo & Kop-->
                    <div class="tab-pane fade" id="tab_logo" role="tabpanel">
                        <!--begin::Form-->
                        <form id="form-logo" class="form" onsubmit="return false">
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-bold fs-6">Logo Instansi</label>
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!--begin::Input group-->
                                            <div class="mb-3" id="iGroup-logo">
                                                <!-- <label class="col-form-label required fw-bold fs-6" for="logo">Logo Instansi</label> -->
                                                <input type="file" class="dropify-upl" id="logo" name="logo" accept=".png, .jpg, .jpeg, .webp" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg webp" data-max-file-size="2M" />
                                                <div class="form-text">*) Type file: <code>*.jpg | *.jpeg | *.png | *.webp</code></div>
                                                <div class="form-text">*) Max. size file: <code>2MB</code></div>
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                        <!--<div class="col-md-6">
                                            <!--begin::Input group--
                                            <div class="mb-3" id="iGroup-kop_surat">
                                                <label class="col-form-label required fw-bold fs-6" for="kop_surat">Kop Surat</label>
                                                <input type="file" class="dropify-upl" id="kop_surat" name="kop_surat" accept=".png, .jpg, .jpeg, .webp" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg webp" data-max-file-size="2M" />
                                                <div class="form-text">*) Type file: <code>*.jpg | *.jpeg | *.png | *.webp</code></div>
                                                <div class="form-text">*) Max. size file: <code>2MB</code></div>
                                            </div>
                                            <!--end::Input group--
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Input group-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Logo & Kop-->
                </div>
                <!--end::Tab content-->
            </div>
            <div class="card-footer d-flex justify-content-end py-3">
                <button type="button" class="btn btn-sm btn-light btn-active-light-danger me-2" id="btn-resetFormProfil"><i class="las la-redo-alt fs-3 me-1"></i>Batal</button>
                <button type="button" class="btn btn-sm btn-success" id="btn-saveProfil">
                    <span class="indicator-label">
                        <i class="las la-save fs-3 me-1"></i>Simpan
                    </span>
                    <span class="indicator-progress">
                        Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </div>
        <!--end::Profil Instansi-->
    </div>
</div>
@endsection
