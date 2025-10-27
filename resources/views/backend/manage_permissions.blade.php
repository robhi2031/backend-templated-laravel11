@extends('backend.layouts', ['activeMenu' => 'Kelola Pengguna', 'activeSubMenu' => 'Permissions'])
@section('content')
<!--begin::Card Form-->
<div class="card" id="card-formPermission" style="display: none;">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title"></div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-bg-light btn-color-danger btn-active-light-danger" id="btn-closeFormPermission" onclick="_closeCard('form_permission');"><i class="fas fa-times fs-3 me-1"></i>Tutup</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Form-->
    <form id="form-permission" class="form" onsubmit="return false">
        <input type="hidden" name="id" />
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Row-->
            <div class="row g-10">
                <div class="col-md-6">
                    <!--begin::Input group-->
                    <div class="mb-3">
                        <label class="col-form-label required fw-bold fs-6" for="name">Permission/ Menu</label>
                        <input type="text" class="form-control form-control-sm form-control-solid" name="name" id="name" maxlength="50" placeholder="Isi nama permission/ menu ..." />
                        <input type="hidden" name="old_parent" /><input type="hidden" name="old_name" />
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-3" id="iGroup-icon">
                        <label class="col-form-label fw-bold fs-6" for="icon">Icon</label>
                        <div class="input-group input-group-sm input-group-solid">
                            <span class="input-group-text">...</span>
                            <input type="text" class="form-control form-control-sm form-control-solid no-space" maxlength="50" name="icon" id="icon" placeholder="Isi icon permission/ menu ..." />
                        </div>
                        <div class="form-text">*) Daftar icon dapat dilihat di <code><a href="https://icons.getbootstrap.com/" target="_blank" class="text-success">https://icons.getbootstrap.com/</a></code></div>
                        <div class="form-text">*) contoh: <code>bi bi-android</code> untuk icon android</div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mb-3">
                        <label class="col-form-label required fw-bold fs-6" for="order_line">Order Line</label>
                        <input type="text" class="form-control form-control-sm form-control-solid no-space" name="order_line" id="order_line" maxlength="12" placeholder="Isi order line ..." />
                        <div class="form-text">*) contoh Parent Permission: <code>1</code></div>
                        <div class="form-text">*) contoh Child Permission: <code>1.1</code></div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mt-5 mb-3">
                        <label class="col-form-label fw-bold fs-6">Route</label>
                        <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid form-check-success flex-stack mb-3">
                            <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                Has Route ?
                            </span>
                            <input class="form-check-input cursor-pointer w-45px h-30px" type="checkbox" name="has_route" id="has_route" value="1" />
                        </label>
                        <!--begin::Input group-->
                        <div class="mb-3" id="iGroup-routeName" style="display: none;">
                            <label class="col-form-label required fw-bold fs-6" for="route_name">Nama Route</label>
                            <input type="text" class="form-control form-control-sm form-control-solid no-space" name="route_name" id="route_name" maxlength="50" placeholder="Isi nama route ..." />
                            <div class="form-text">*) contoh: <code>manage_permissions</code> untuk permission/ menu <code>Permission</code></div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Input group-->
                </div>
                <div class="col-md-6">
                    <!--begin::Input group-->
                    <div class="mt-5 mb-3">
                        <label class="col-form-label fw-bold fs-6">Parent</label>
                        <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid form-check-success flex-stack mb-3">
                            <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                Has Parent ?
                            </span>
                            <input class="form-check-input cursor-pointer w-45px h-30px" type="checkbox" name="has_parent" id="has_parent" value="1" />
                        </label>
                        <!--begin::Input group-->
                        <div class="mb-3" id="iGroup-cboParent" style="display: none;">
                            <label class="col-form-label required fw-bold fs-6" for="cbo_parent">Nama Parent</label>
                            <select class="form-select form-select-sm form-select-solid" name="cbo_parent" id="cbo_parent"></select>
                            <div class="form-text">*) Ketik parent permission/ menu untuk mempercepat pencarian</div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mt-5 mb-3">
                        <label class="col-form-label fw-bold fs-6">Child</label>
                        <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid form-check-success flex-stack mb-3">
                            <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                Has Child ?
                            </span>
                            <input class="form-check-input cursor-pointer w-45px h-30px" type="checkbox" name="has_child" id="has_child" value="1" />
                        </label>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="mt-5 mb-3">
                        <label class="col-form-label fw-bold fs-6">CRUD</label>
                        <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid form-check-success flex-stack mb-3">
                            <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">
                                Is CRUD ?
                            </span>
                            <input class="form-check-input cursor-pointer w-45px h-30px" type="checkbox" name="is_crud" id="is_crud" value="1" />
                        </label>
                        <!--begin::Input group-->
                        <div class="row g-2 mt-3 mb-3" id="iGroup-crud" style="display: none;">
                            <label class="col-md-6 form-check form-switch form-switch-sm form-check-solid form-check-success flex-stack">
                                <span class="form-check-label cursor-pointer ms-0 fw-bold fs-6 text-gray-700">
                                    CREATE
                                </span>
                                <input class="form-check-input crud cursor-pointer w-45px h-30px" type="checkbox" name="create" id="create" value="1" />
                            </label>
                            <label class="col-md-6 form-check form-switch form-switch-sm form-check-solid form-check-success flex-stack">
                                <span class="form-check-label cursor-pointer ms-0 fw-bold fs-6 text-gray-700">
                                    READ
                                </span>
                                <input class="form-check-input crud cursor-pointer w-45px h-30px" type="checkbox" name="read" id="read" value="1" />
                            </label>
                            <label class="col-md-6 form-check form-switch form-switch-sm form-check-solid form-check-success flex-stack">
                                <span class="form-check-label cursor-pointer ms-0 fw-bold fs-6 text-gray-700">
                                    UPDATE
                                </span>
                                <input class="form-check-input crud cursor-pointer w-45px h-30px" type="checkbox" name="update" id="update" value="1" />
                            </label>
                            <label class="col-md-6 form-check form-switch form-switch-sm form-check-solid form-check-success flex-stack">
                                <span class="form-check-label cursor-pointer ms-0 fw-bold fs-6 text-gray-700">
                                    DELETE
                                </span>
                                <input class="form-check-input crud cursor-pointer w-45px h-30px" type="checkbox" name="delete" id="delete" value="1" />
                            </label>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Card body-->
        <!--begin::Actions-->
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="button" class="btn btn-sm btn-light btn-active-light-danger me-2" id="btn-reset" onclick="_clearFormPermission();"><i class="las la-redo-alt fs-3 me-1"></i>Batal</button>
            <button type="button" class="btn btn-sm btn-success" id="btn-save">
                <span class="indicator-label">
                    <i class="las la-save fs-3 me-1"></i>Simpan
                </span>
                <span class="indicator-progress">
                    Mohon tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->
</div>
<!--end::Card Form-->
<!--begin::List Table Data-->
<div class="card shadow" id="card-dtPermissions">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900"></i> Data Permissions
            </h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-outline btn-outline-success btn-active-light-success bg-gray-900" id="btn-addPermission" onclick="_addPermission();"><i class="las la-plus fs-3 me-1"></i>Tambah</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-center align-items-center mb-5">
            <div class="ms-auto">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative mb-md-0 mb-3">
                    <div class="input-group input-group-sm input-group-solid border">
                        <span class="input-group-text"><i class="las la-search fs-3"></i></span>
                        <input type="text" class="form-control form-control-sm form-control-solid border-left-0" name="search-dtPermissions" id="search-dtPermissions" placeholder="Pencarian..." />
                        <span class="input-group-text border-left-0 cursor-pointer text-hover-danger" id="clear-searchDtPermissions" style="display: none;">
                            <i class="las la-times fs-3"></i>
                        </span>
                    </div>
                </div>
                <!--end::Search-->
            </div>
        </div>
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded align-middle table-row-bordered border" id="dt-permissions">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-light">
                        <!-- <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th> -->
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">Aksi</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Menu/ Permission</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Icon</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Route</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Parent</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Has Child</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">CRUD</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Order Line</th>
                    </tr>
                </thead>
            </table>
            <!--end::Table-->
        </div>
    </div>
</div>
<!--end::List Table Data-->
@endsection
