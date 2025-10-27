"use strict";
// Class Definition
//Load this Month Year
let thisMonth = new Date().getMonth() + 1;
if (thisMonth < 10) {
    thisMonth = '0' + thisMonth;
}
let thisYear = new Date().getFullYear();
thisMonth = thisMonth.toString();
thisYear = thisYear.toString();
let monthYear = thisMonth+ '/' +thisYear;
//CounterUp Function
const _loadCounterUp = (target, num) => {
    let optionsCount = {
        useEasing: false,
        useGrouping: false,
    }, countKend = new countUp.CountUp(target, num, optionsCount);
    countKend.start();
}
//Load Content Widget01
const _loadWidget01 = () => {
    $.ajax({
        url: base_url+ "api/dashboard/show",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        data: {
            is_widget: true,
            widget_number: 1
        },
        dataType: 'JSON',
        success: function (data) {
            //Count Reg. Today
            let cRegToday = data.row.counterRegToday,
                ttlRegToday = cRegToday.total,
                ttlRegYesterday = cRegToday.total_yesterday,
                percentageRegToday = cRegToday.percentage,
                jnsPenerbitanTodays = cRegToday.status_penerbitan,
                persentageRegTodayB = `<span class="badge badge-light-success fs-base" title="Naik ` +percentageRegToday+ `% dari hari sebelumnya">
                    <i class="ki-duotone ki-arrow-up fs-5 text-success ms-n1"><span class="path1"></span><span class="path2"></span></i>
                    <span id="persentageRegToday">0</span>%
                </span>`;
            //Custom Persentage Today
            if(ttlRegToday < ttlRegYesterday) {
                persentageRegTodayB = `<span class="badge badge-light-danger fs-base" title="Turun ` +percentageRegToday+ `% dari hari sebelumnya">
                    <i class="ki-duotone ki-arrow-down fs-5 text-danger ms-n1"><span class="path1"></span><span class="path2"></span></i>
                    <span id="persentageRegToday">0</span>%
                </span>`;
            } if(ttlRegToday == ttlRegYesterday) {
                persentageRegTodayB = `<span class="badge badge-light-primary fs-base" title="Sama dengan hari sebelumnya">
                    <span id="persentageRegToday">0</span>%
                </span>`;
            }
            $('#cardCountRegToday .card-header .card-title').html(`<div class="d-flex align-items-center">
                <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2" id="countRegToday">0</span>
                ` +persentageRegTodayB+ `
            </div>
            <span class="text-gray-500 pt-1 fw-semibold fs-6">Pendaftaran Hari Ini</span>`);
            //for Body Count Reg Today
            let bodyCountRegToday='';
            bodyCountRegToday += `<div class="d-flex flex-column content-justify-center flex-row-fluid">`;
            $.each(jnsPenerbitanTodays, function(key, item) {
                let name = item.name;
                if(item.idp == 3 || item.idp == 4) {
                    name = 'BLUE ' +item.name;
                }
                bodyCountRegToday += `<div class="d-flex fw-semibold align-items-center my-1">
                    <div class="bullet w-8px h-3px rounded-2 bg-secondary me-3"></div>
                    <div class="text-gray-500 flex-grow-1 me-4">` +name+ `</div>
                    <div class="fw-bolder text-gray-700 text-xxl-end">` +item.total+ `</div>
                </div>`;
            });
            bodyCountRegToday += `</div>`;
            $('#cardCountRegToday .card-body').html(bodyCountRegToday);
            //Counter Today
            _loadCounterUp('countRegToday', ttlRegToday), _loadCounterUp('persentageRegToday', percentageRegToday);
            //Count Reg. Today :: end
            //Count Reg. This Month :: start
            let cRegThisMonth = data.row.counterRegThisMonth,
                ttlRegThisMonth = cRegThisMonth.total,
                ttlRegLastMonth = cRegThisMonth.total_last_month,
                percentageRegThisMonth = cRegThisMonth.percentage,
                jnsPenerbitanThisMonths = cRegThisMonth.status_penerbitan,
                persentageRegThisMonthB = `<span class="badge badge-light-success fs-base" title="Naik ` +percentageRegThisMonth+ `% dari bulan lalu">
                    <i class="ki-duotone ki-arrow-up fs-5 text-success ms-n1"><span class="path1"></span><span class="path2"></span></i>
                    <span id="persentageRegThisMonth">0</span>%
                </span>`;
            //Custom Persentage This Month
            if(ttlRegThisMonth < ttlRegLastMonth) {
                persentageRegThisMonthB = `<span class="badge badge-light-danger fs-base" title="Turun ` +percentageRegThisMonth+ `% dari bulan lalu">
                    <i class="ki-duotone ki-arrow-down fs-5 text-danger ms-n1"><span class="path1"></span><span class="path2"></span></i>
                    <span id="persentageRegThisMonth">0</span>%
                </span>`;
            } if(ttlRegThisMonth == ttlRegLastMonth) {
                persentageRegThisMonthB = `<span class="badge badge-light-primary fs-base" title="Sama dengan bulan lalu">
                    <span id="persentageRegThisMonth">0</span>%
                </span>`;
            }
            $('#cardCountRegThisMonth .card-header .card-title').html(`<div class="d-flex align-items-center">
                <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2" id="countRegThisMonth">0</span>
                ` +persentageRegThisMonthB+ `
            </div>
            <span class="text-gray-500 pt-1 fw-semibold fs-6">Pendaftaran Bulan Ini</span>`);
            //for Body Count Reg This Month
            let bodyCountRegThisMonth='';
            bodyCountRegThisMonth += `<div class="d-flex flex-column content-justify-center flex-row-fluid">`;
            $.each(jnsPenerbitanThisMonths, function(key, item) {
                let name = item.name;
                if(item.idp == 3 || item.idp == 4) {
                    name = 'BLUE ' +item.name;
                }
                bodyCountRegThisMonth += `<div class="d-flex fw-semibold align-items-center my-1">
                    <div class="bullet w-8px h-3px rounded-2 bg-secondary me-3"></div>
                    <div class="text-gray-500 flex-grow-1 me-4">` +name+ `</div>
                    <div class="fw-bolder text-gray-700 text-xxl-end">` +item.total+ `</div>
                </div>`;
            });
            bodyCountRegThisMonth += `</div>`;
            $('#cardCountRegThisMonth .card-body').html(bodyCountRegThisMonth);
            //Counter This Month
            _loadCounterUp('countRegThisMonth', ttlRegThisMonth), _loadCounterUp('persentageRegThisMonth', percentageRegThisMonth);
            //Count Reg. This Month :: end
            //Count Reg. This Year :: start
            let cRegThisYear = data.row.counterRegThisYear,
                ttlRegThisYear = cRegThisYear.total,
                ttlRegLastYear = cRegThisYear.total_last_month,
                percentageRegThisYear = cRegThisYear.percentage,
                jnsPenerbitanThisYears = cRegThisYear.status_penerbitan,
                persentageRegThisYearB = `<span class="badge badge-light-success fs-base" title="Naik ` +percentageRegThisYear+ `% dari tahun lalu">
                    <i class="ki-duotone ki-arrow-up fs-5 text-success ms-n1"><span class="path1"></span><span class="path2"></span></i>
                    <span id="persentageRegThisYear">0</span>%
                </span>`;
            //Custom Persentage This Year
            if(ttlRegThisYear < ttlRegLastYear) {
                persentageRegThisYearB = `<span class="badge badge-light-danger fs-base" title="Turun ` +percentageRegThisYear+ `% dari tahun lalu">
                    <i class="ki-duotone ki-arrow-down fs-5 text-danger ms-n1"><span class="path1"></span><span class="path2"></span></i>
                    <span id="persentageRegThisYear">0</span>%
                </span>`;
            } if(ttlRegThisYear == ttlRegLastYear) {
                persentageRegThisYearB = `<span class="badge badge-light-primary fs-base" title="Sama dengan tahun lalu">
                    <span id="persentageRegThisYear">0</span>%
                </span>`;
            }
            $('#cardCountRegThisYear .card-header .card-title').html(`<div class="d-flex align-items-center">
                <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2" id="countRegThisYear">0</span>
                ` +persentageRegThisYearB+ `
            </div>
            <span class="text-gray-500 pt-1 fw-semibold fs-6">Pendaftaran Tahun Ini</span>`);
            //for Body Count Reg This Year
            let bodyCountRegThisYear='';
            bodyCountRegThisYear += `<div class="d-flex flex-column content-justify-center flex-row-fluid">`;
            $.each(jnsPenerbitanThisYears, function(key, item) {
                let name = item.name;
                if(item.idp == 3 || item.idp == 4) {
                    name = 'BLUE ' +item.name;
                }
                bodyCountRegThisYear += `<div class="d-flex fw-semibold align-items-center my-1">
                    <div class="bullet w-8px h-3px rounded-2 bg-secondary me-3"></div>
                    <div class="text-gray-500 flex-grow-1 me-4">` +name+ `</div>
                    <div class="fw-bolder text-gray-700 text-xxl-end">` +item.total+ `</div>
                </div>`;
            });
            bodyCountRegThisYear += `</div>`;
            $('#cardCountRegThisYear .card-body').html(bodyCountRegThisYear);
            //Counter This Year
            _loadCounterUp('countRegThisYear', ttlRegThisYear), _loadCounterUp('persentageRegThisYear', percentageRegThisYear);
            //Count Reg. This Year :: end
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
// Load Content Widget02
const _loadWidget02 = () => {
    $.ajax({
        url: base_url+ "api/dashboard/show",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        data: {
            is_widget: true,
            widget_number: 2
        },
        dataType: 'JSON',
        success: function (data) {
            //Total Pendaftaran
            let cReg = data.row.counterReg,
                ttlReg = cReg.total,
                ttlBlue = cReg.penerbitan_blue,
                ttlRekom = cReg.penerbitan_rekom;
            _loadCounterUp('countReg', ttlReg), _loadCounterUp('countRegBlue', ttlBlue), _loadCounterUp('countRegRekom', ttlRekom);
            //Total Pengujian
            let cUji = data.row.counterUji,
                ttlUji = cUji.total,
                ttlLulus = cUji.lulus,
                ttlTidakLulus = cUji.tidak_lulus;
            _loadCounterUp('countPengujian', ttlUji), _loadCounterUp('countLulus', ttlLulus), _loadCounterUp('countTidakLulus', ttlTidakLulus);
            //Total Taman Kendaraan
            let cTamanKend = data.row.counterTamanKend,
                ttlKend = cTamanKend.total,
                ttlAktif = cTamanKend.aktif,
                ttlTidakAktif = cTamanKend.tidak_aktif;
            _loadCounterUp('countTamanKend', ttlKend), _loadCounterUp('countAktif', ttlAktif), _loadCounterUp('countTidakAktif', ttlTidakAktif);
            //Total Kendaraan Numpang Uji Masuk
            let cKendNumpang = data.row.counterKendNumpang,
                ttlKendNumpang = cKendNumpang.total,
                ttlNumpangAktif = cKendNumpang.aktif,
                ttlNumpangTidakAktif = cKendNumpang.tidak_aktif;
            _loadCounterUp('countKendNumpangUji', ttlKendNumpang), _loadCounterUp('countNumpangAktif', ttlNumpangAktif), _loadCounterUp('countNumpangTidakAktif', ttlNumpangTidakAktif);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
//Load Content Widget03
const _loadGrafikChartWidget03 = (monthYearFilter) => {
    let target = document.querySelector('#card-highcharts01'), blockUi = new KTBlockUI(target, { message: messageBlockUi });
        blockUi.block(), blockUi.destroy();
    $.ajax({
        url : base_url+ 'api/dashboard/show',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        data: {
            is_widget: true,
            widget_number: 3,
            month_year: monthYearFilter,
        }, success: function(data) {
            let options,
                month_year = data.row[0].month_year,
                categories_text = data.row[1].name,
                categories = data.row[1].data,
                dates = data.row[1].data_dates,
                regName = data.row[2].reg.name,
                regData = data.row[2].reg.data,
                blueName = data.row[3].blue.name,
                blueData = data.row[3].blue.data,
                rekomName = data.row[4].rekom.name,
                rekomData = data.row[4].rekom.data;
            //change Label Text
            $('#lbl-chartRegMonthYear').text(month_year);
            //load Highcharts Data
            let series = [
                {
                    name: regName,
                    data: regData
                },
                {
                    name: blueName,
                    data: blueData
                },
                {
                    name: rekomName,
                    data: rekomData
                }
            ];
            options = {
                chart: {
                    renderTo: 'chartRegMonthYear',
                    type: 'line'
                },
                title: {
                    text: null
                },
                credits: {
                    enabled: false
                },
                xAxis: {
                    categories: categories,
                    title: {
                        text: categories_text
                    }
                },
                yAxis: {
                    title: {
                        text: 'Jumlah'
                    }
                },
                tooltip: {
                    shared: true,
                    formatter: function () {
                        let x = this.point.x;
                        let tooltipCustom = `<div class="m-0">
                            <div class="fs-6 fw-bold">Tgl. ` +dates[x]+ `</div><br />
                            <div class="fs-7 fw-semibold"><span style="color:` +this.points[0].color+ `;">\u25CF</span> ` +this.points[0].series.name+ `: <strong>` +this.points[0].y+ `</strong></div><br />
                            <div class="fs-7 fw-semibold"><span style="color:` +this.points[1].color+ `;">\u25CF</span> ` +this.points[1].series.name+ `: <strong>` +this.points[1].y+ `</strong></div><br />
                            <div class="fs-7 fw-semibold"><span style="color:` +this.points[2].color+ `;">\u25CF</span> ` +this.points[2].series.name+ `: <strong>` +this.points[2].y+ `</strong></div>
                        </div>`;
                        return tooltipCustom;
                    }
                },
                series: series,
                exporting: {
                    buttons: {
                        contextButton: {
                            menuItems: ['downloadPNG', 'downloadJPEG']
                        }
                    }
                }
            };
            new Highcharts.Chart(options);
            blockUi.release(), blockUi.destroy();
        }, error: function (jqXHR, textStatus, errorThrown){
            blockUi.release(), blockUi.destroy();
            console.log('Load data is error!');
        }
    });
}
// Class Initialization
jQuery(document).ready(function() {
    _loadWidget01(), _loadWidget02(), _loadGrafikChartWidget03(monthYear);
    //Load Month Year Picker
    $("#filter-chartRegMonthYear").datepicker({
        format: "mm/yyyy",
        viewMode: 1,
        minViewMode: 1,
        language: "id",
        autoclose: true
    }).datepicker("setDate", monthYear).val(monthYear).change(function () {
        let monthYearFilter = $(this).val();
        monthYear = monthYearFilter;
        _loadGrafikChartWidget03(monthYearFilter);
    });
});
