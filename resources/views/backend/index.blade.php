@extends('backend.layouts', ['activeMenu' => 'DASHBOARD', 'activeSubMenu' => ''])
@section('content')
<!--begin::Row-->
<div class="row g-5 g-xl-10 mb-5 mb-xl-10" style="display: none;">
    <!--begin::Col-->
    <div class="col-xl-12">
        <!--begin::Card widget 1-->
        <div class="row g-5">
            <div class="col-xl-4">
                <div class="card card-flush border-success" id="cardCountRegToday">
                    <!--begin::Header-->
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">0</span>
                                <span class="badge badge-light-primary fs-base">
                                    0%
                                </span>
                            </div>
                            <span class="text-gray-500 pt-1 fw-semibold fs-6">Pendaftaran Hari Ini</span>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center"></div>
                    <!--end::Card body-->
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card card-flush border-primary" id="cardCountRegThisMonth">
                    <!--begin::Header-->
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">0</span>
                                <span class="badge badge-light-primary fs-base">
                                    0%
                                </span>
                            </div>
                            <span class="text-gray-500 pt-1 fw-semibold fs-6">Pendaftaran Bulan Ini</span>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center"></div>
                    <!--end::Card body-->
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card card-flush border-info" id="cardCountRegThisYear">
                    <!--begin::Header-->
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">0</span>
                                <span class="badge badge-light-primary fs-base">
                                    0%
                                </span>
                            </div>
                            <span class="text-gray-500 pt-1 fw-semibold fs-6">Pendaftaran Tahun Ini</span>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center"></div>
                    <!--end::Card body-->
                </div>
            </div>
        </div>
        <!--end::Card widget 1-->
        <!--start::Card widget 2-->
        <div class="row g-5 mt-5">
            <div class="col-xl-3">
                <div class="card bg-success">
                    <div class="card-body py-3 px-5">
                        <div class="fw-semibold text-white mb-1">
                            Total Pendaftaran
                        </div>
                        <div class="d-flex flex-stack align-items-center">
                            <span class="ms-n1">
                                <i class="text-white ki-outline ki-folder-added fs-3x"></i>
                            </span>
                            <div class="text-white fw-bold fs-2x" id="countReg">0</div>
                        </div>
                        <div class="d-flex flex-column content-justify-center flex-row-fluid mt-3">
                            <div class="d-flex fw-semibold align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-secondary me-3"></div>
                                <div class="text-white flex-grow-1 me-4">Penerbitan BLUE</div>
                                <div class="fw-bolder text-white text-xxl-end" id="countRegBlue">0</div>
                            </div>
                            <div class="d-flex fw-semibold align-items-center my-1">
                                <div class="bullet w-8px h-3px rounded-2 bg-secondary me-3"></div>
                                <div class="text-white flex-grow-1 me-4">Penerbitan Rekom</div>
                                <div class="fw-bolder text-white text-xxl-end" id="countRegRekom">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card bg-primary">
                    <div class="card-body py-3 px-5">
                        <div class="fw-semibold text-white mb-1">
                            Total Pengujian
                        </div>
                        <div class="d-flex flex-stack align-items-center">
                            <span class="ms-n1">
                                <i class="text-white ki-outline ki-medal-star fs-3x"></i>
                            </span>
                            <div class="text-white fw-bold fs-2x" id="countPengujian">0</div>
                        </div>
                        <div class="d-flex flex-column content-justify-center flex-row-fluid mt-3">
                            <div class="d-flex fw-semibold align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-secondary me-3"></div>
                                <div class="text-white flex-grow-1 me-4">Lulus Uji</div>
                                <div class="fw-bolder text-white text-xxl-end" id="countLulus">0</div>
                            </div>
                            <div class="d-flex fw-semibold align-items-center my-1">
                                <div class="bullet w-8px h-3px rounded-2 bg-secondary me-3"></div>
                                <div class="text-white flex-grow-1 me-4">Tidak Lulus</div>
                                <div class="fw-bolder text-white text-xxl-end" id="countTidakLulus">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card bg-info">
                    <div class="card-body py-3 px-5">
                        <div class="fw-semibold text-white mb-1">
                            Total Taman Kendaraan
                        </div>
                        <div class="d-flex flex-stack align-items-center">
                            <span class="ms-n1">
                                <i class="text-white ki-outline ki-truck fs-3x"></i>
                            </span>
                            <div class="text-white fw-bold fs-2x" id="countTamanKend">0</div>
                        </div>
                        <div class="d-flex flex-column content-justify-center flex-row-fluid mt-3">
                            <div class="d-flex fw-semibold align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-secondary me-3"></div>
                                <div class="text-white flex-grow-1 me-4">Aktif Uji</div>
                                <div class="fw-bolder text-white text-xxl-end" id="countAktif">0</div>
                            </div>
                            <div class="d-flex fw-semibold align-items-center my-1">
                                <div class="bullet w-8px h-3px rounded-2 bg-secondary me-3"></div>
                                <div class="text-white flex-grow-1 me-4">Mati Uji</div>
                                <div class="fw-bolder text-white text-xxl-end" id="countTidakAktif">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card bg-danger">
                    <div class="card-body py-3 px-5">
                        <div class="fw-semibold text-white mb-1">
                            Total Kendaraan Numpang Uji Masuk
                        </div>
                        <div class="d-flex flex-stack align-items-center">
                            <span class="ms-n1">
                                <i class="text-white ki-outline ki-truck fs-3x"></i>
                            </span>
                            <div class="text-white fw-bold fs-2x" id="countKendNumpangUji">0</div>
                        </div>
                        <div class="d-flex flex-column content-justify-center flex-row-fluid mt-3">
                            <div class="d-flex fw-semibold align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-secondary me-3"></div>
                                <div class="text-white flex-grow-1 me-4">Aktif Uji</div>
                                <div class="fw-bolder text-white text-xxl-end" id="countNumpangAktif">0</div>
                            </div>
                            <div class="d-flex fw-semibold align-items-center my-1">
                                <div class="bullet w-8px h-3px rounded-2 bg-secondary me-3"></div>
                                <div class="text-white flex-grow-1 me-4">Mati Uji</div>
                                <div class="fw-bolder text-white text-xxl-end" id="countNumpangTidakAktif">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Card widget 2-->
        <!--begin::Card widget 3-->
        <div class="row g-5 mt-5">
            <div class="col-xl-12">
                <div class="card card-xl-stretch mb-xl-8" id="card-highcharts01">
                    <div class="card-header border-0">
                        <div class="card-title">
                            <h3 class="fw-bolder fs-2 text-gray-900">
                                <i class="ki-outline ki-chart-line-up-2 fs-2 text-gray-900 me-2"></i> Grafik Trend <span class="text-success" id="lbl-chartRegMonthYear"></span>
                            </h3>
                        </div>
                        <div class="card-toolbar">
                            <!--begin::Filter-->
                            <div class="d-flex flex-column align-items-center position-relative mb-md-0 mb-3">
                                <!-- <label class="col-form-label fw-bold fs-6" for="filter-chartProduksi">Tahun:</label> -->
                                <div class="input-group input-group-sm input-group-solid border">
                                    <span class="input-group-text"><i class="bi bi-calendar fs-3"></i></span>
                                    <input type="text" class="form-control form-control-sm form-control-solid border-left-0 cursor-pointer" name="filter-chartRegMonthYear" id="filter-chartRegMonthYear" placeholder="Filter Bulan Tahun ..." readonly />
                                </div>
                            </div>
                            <!--end::Filter-->
                        </div>
                    </div>
                    <div class="card-body pt-3">
                        <figure class="highcharts-figure">
                            <div id="chartRegMonthYear" class="h-350px h-md-325px"></div>
                        </figure>
                    </div>
                </div>
            </div>
        </div>
        <!--begin::Card widget 3-->
    </div>
    <!--end::Col-->
</div>
<!--end::Row-->
@endsection
