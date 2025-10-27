@extends('backend.layouts', ['activeMenu' => '', 'activeSubMenu' => ''])
@section('content')
<!--begin::User Info-->
<div class="card mb-5 mb-xl-10" id="cardUserInfo">
    <!--begin::Details-->
    <div class="card-body" id="dtlUserInfo"></div>
    <!--end::Details-->
    <!--begin::Edit-->
    <div class="card-body" id="editUserInfo" style="display: none;">
        <div class="d-flex justify-content-between align-items-start flex-wrap">
            <h3 class="fw-bolder m-0 mb-3"><i class="las la-user-edit text-dark fs-2 me-3"></i>Edit Profil Saya</h3>
            <a href="javascript:void(0);" class="btn btn-sm btn-bg-light btn-color-danger btn-active-light-danger ms-3" onclick="_closeContentCard('edit-userProfile')"><i class="las la-undo fs-3 me-1"></i>Kembali</a>
        </div>
        <div class="separator my-5 mb-md-10"></div>
        <!--begin::Form-->
        <form id="form-editProfile" class="form" onsubmit="return false">
            <!--begin::Input group-->
            <div class="row mb-6" id="iGroup-fotoUser">
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Foto User</label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('{{ asset('/dist/img/avatar-blank.svg') }}')">
                                <!--begin::Preview existing avatar-->
                                <div class="image-input-wrapper w-125px h-125px"></div>
                                <!--end::Preview existing avatar-->
                                <label class="btn btn-icon btn-circle btn-active-color-success w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Ubah foto user">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <input type="file" name="avatar" id="avatar" accept=".png, .jpg, .jpeg, .webp" />
                                    <input type="hidden" name="avatar_remove" id="avatar_remove" value="1" />
                                </label>
                                <span class="btn btn-icon btn-circle btn-active-color-success w-25px h-25px bg-body shadow" id="btn-cancelFotoUser" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Batalkan perubahan foto user">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                <span class="btn btn-icon btn-circle btn-active-color-success w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Hapus foto user">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-xl-9">
                            <div class="form-text">*) Jenis file yang diizinkan: <code>*.png, *.jpg, *.jpeg, *.webp</code></div>
                            <div class="form-text">*) Ukuran file Maks: <code>2MB</code></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="name">Nama</label>
                <div class="col-lg-8">
                    <input type="text" name="name" id="name" class="form-control form-control-sm form-control-solid mb-3 mb-lg-0" maxlength="100" placeholder="Isikan nama ..." />
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="username">Username</label>
                <div class="col-lg-8">
                    <div class="input-group input-group-sm input-group-solid mb-2">
                        <span class="input-group-text"><i class="las la-user fs-1"></i></span>
                        <input type="text" class="form-control form-control-sm form-control-solid no-space" name="username" id="username" readonly />
                    </div>
                    <div class="form-text">*) Tanpa spasi, contoh: <code>andre123</code></div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="email">Email</label>
                <div class="col-lg-8">
                    <div class="input-group input-group-sm input-group-solid mb-2">
                        <span class="input-group-text"><i class="las la-envelope fs-1"></i></span>
                        <input type="text" class="form-control form-control-sm form-control-solid no-space" name="email" id="email" placeholder="Isikan email ..." />
                    </div>
                    <div class="form-text">*) Pastikan email sesuai format dan masih aktif digunakan, contoh: <code>andre123@gmail.com</code></div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="phone_number">No. Telpon/Hp</label>
                <div class="col-lg-8">
                    <div class="input-group input-group-sm input-group-solid mb-2">
                        <span class="input-group-text"><i class="las la-phone fs-1"></i></span>
                        <input type="text" class="form-control form-control-sm form-control-solid no-space" name="phone_number" id="phone_number" placeholder="Isikan No. Telpon/Hp ..." />
                    </div>
                    <div class="form-text">*) Pastikan No. Telpon/Hp sesuai format dan masih aktif digunakan, contoh: <code>6283122222222</code></div>
                </div>
            </div>
            <!--end::Input group-->
            <div class="row mt-5">
                <div class="col-lg-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-light btn-active-light-danger me-2" id="btn-resetFormMyProfile"><i class="las la-redo-alt fs-3 me-1"></i>Batal</button>
                    <button type="button" class="btn btn-sm btn-success" id="btn-saveMyProfile">
                        <span class="indicator-label">
                            <i class="las la-save fs-3 me-1"></i>Simpan
                        </span>
                        <span class="indicator-progress">
                            Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!--end::Edit-->
    <!--begin::Change Pass-->
    <div class="card-body" id="changePassUserInfo" style="display: none;">
        <div class="d-flex justify-content-between align-items-start flex-wrap">
            <h3 class="fw-bolder m-0 mb-3"><i class="las la-user-lock text-dark fs-2 me-3"></i>Ubah Password</h3>
            <a href="javascript:void(0);" class="btn btn-sm btn-bg-light btn-color-danger btn-active-light-danger" onclick="_closeContentCard('changePass-userProfile')"><i class="las la-undo fs-3 me-1"></i>Kembali</a>
        </div>
        <div class="separator my-5 mb-md-10"></div>
        <!--begin::Form-->
        <form id="form-changePass" class="form" onsubmit="return false">
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="pass_lama">Password Lama</label>
                <div class="col-lg-8">
                    <div class="input-group input-group-sm input-group-solid">
                        <input type="password" class="form-control form-control-sm form-control-solid no-space password" name="pass_lama" id="pass_lama" minlength="6" placeholder="Isikan password lama ..." />
                        <span class="input-group-text cursor-pointer btn-showPass" title="Sembunyikan password"><i class="las la-eye-slash fs-1"></i></span>
                    </div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="pass_baru">Password Baru</label>
                <div class="col-lg-8">
                    <div class="input-group input-group-sm input-group-solid">
                        <input type="password" class="form-control form-control-sm form-control-solid no-space password" name="pass_baru" id="pass_baru" minlength="6" placeholder="Isikan password baru ..." />
                        <span class="input-group-text cursor-pointer btn-showPass" title="Sembunyikan password"><i class="las la-eye-slash fs-1"></i></span>
                    </div>
                    <div class="form-text">*) Tanpa spasi, Panjang karakter minimal 6 | contoh: <code>Admin.2020</code></div>
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-bold fs-6" for="repass_baru">Ulangi Password Baru</label>
                <div class="col-lg-8">
                    <div class="input-group input-group-sm input-group-solid mb-5">
                        <input type="password" class="form-control form-control-sm form-control-solid no-space password" name="repass_baru" id="repass_baru" minlength="6" placeholder="Ulangi password baru ..." />
                        <span class="input-group-text cursor-pointer btn-showPass" title="Sembunyikan password"><i class="las la-eye-slash fs-1"></i></span>
                    </div>
                    <div class="form-text">*) Harus sama dengan password baru</div>
                </div>
            </div>
            <!--end::Input group-->
            <!--end::Input group-->
            <div class="row mt-5">
                <div class="col-lg-12 d-flex justify-content-end">
                    <button type="button" class="btn btn-sm btn-light btn-active-light-danger me-2" id="btn-resetPassUser"><i class="las la-redo-alt fs-3 me-1"></i>Batal</button>
                    <button type="button" class="btn btn-sm btn-success" id="btn-savePassUser">
                        <span class="indicator-label">
                            <i class="las la-save fs-3 me-1"></i> Simpan
                        </span>
                        <span class="indicator-progress">
                            Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
        <!--end::Form-->
    </div>
    <!--end::Change Pass-->
</div>
<!--end::User Info-->
@endsection
