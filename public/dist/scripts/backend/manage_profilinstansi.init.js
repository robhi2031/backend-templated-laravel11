"use strict";
// Class Definition
//start::Load Maps Lokasi
let map, marker, geocoder,
    latitude = -6.9641078, longitude = 108.8037628;
function initializeMap() {
    return new Promise((resolve, reject) => {
        let $latitude = document.getElementById("latitudeAddress");
        let $longitude = document.getElementById("longitudeAddress");
        let zoom = 7;
        let LatLng = new google.maps.LatLng(latitude, longitude);
        let mapOptions = {
            zoom: zoom,
            center: LatLng,
            panControl: false,
            zoomControl: false,
            scaleControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        };
        map = new google.maps.Map(document.getElementById("mapAddress"), mapOptions);
        geocoder = new google.maps.Geocoder();
        if (marker && marker.getMap) marker.setMap(map);
        marker = new google.maps.Marker({
            position: LatLng,
            map: map,
            title: "Tentukan titik lokasi Alamat Kantor",
            draggable: true,
        });
        google.maps.event.addListener(marker, "dragend", function (marker) {
            var latLng = marker.latLng;
            $latitude.value = latLng.lat();
            $longitude.value = latLng.lng();
            geocoder.geocode(
                { latLng: marker.latLng },
                function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            $("#address").val(results[0].formatted_address);
                        }
                    }
                }
            );
        });

        resolve();
    });
}
//end::Load Maps Lokasi
// Search Maps Lokasi
const _searchMapAddress = (address) => {
    geocoder.geocode({ address: address }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location),
            marker.setPosition(results[0].geometry.location),
            map.setZoom(18);
            $("#latitudeAddress").val(marker.getPosition().lat());
            $("#longitudeAddress").val(marker.getPosition().lng());
            $("#address").val(results[0].formatted_address);
        } else {
            toastr.error("Geocode tidak berhasil karena alasan berikut" +status, "Uuppss!", { progressBar: true, timeOut: 1500 });
        }
    });
}
// Search Maps Address by Button
$("#btn-searchAddress").on("click", function (e) {
    e.preventDefault();
    let address = $('#searchMapAddress').val();
    _searchMapAddress(address);
});
// Search Maps Address by Enter
$('#searchMapAddress').keyup(function (e) {
    if (e.keyCode == 13 || e.key === "Enter") {
        let address = $(this).val();
        _searchMapAddress(address);
    }
    e.preventDefault();
});
//end::Load Maps Lokasi
let tabActive = "tab_general";
let roleActive = $('[name="roleActive"]').val();
//Load File Dropify
const _loadDropifyFile = (url_file, paramsId) => {
    if (url_file == "") {
        let drEvent1 = $(paramsId).dropify({
            defaultFile: "",
        });
        drEvent1 = drEvent1.data("dropify");
        drEvent1.resetPreview();
        drEvent1.clearElement();
        drEvent1.settings.defaultFile = "";
        drEvent1.destroy();
        drEvent1.init();
    } else {
        let drEvent1 = $(paramsId).dropify({
            defaultFile: url_file,
        });
        drEvent1 = drEvent1.data("dropify");
        drEvent1.resetPreview();
        drEvent1.clearElement();
        drEvent1.settings.defaultFile = url_file;
        drEvent1.destroy();
        drEvent1.init();
    }
};
//begin::Dropify
$(".dropify-upl").dropify({
    messages: {
        default: '<span class="btn btn-sm btn-secondary">Drag/ drop file atau Klik disini</span>',
        replace: '<span class="btn btn-sm btn-primary"><i class="fas fa-upload"></i> Drag/ drop atau Klik untuk menimpa file</span>',
        remove: '<span class="btn btn-sm btn-danger"><i class="las la-trash-alt"></i> Reset</span>',
        error: 'Ooops, Terjadi kesalahan pada file input',
    },
    error: {
        fileSize: 'Ukuran file terlalu besar, Max. ( {{ value }} )',
        minWidth: 'Lebar gambar terlalu kecil, Min. ( {{ value }}}px )',
        maxWidth: 'Lebar gambar terlalu besar, Max. ( {{ value }}}px )',
        minHeight: 'Tinggi gambar terlalu kecil, Min. ( {{ value }}}px )',
        maxHeight: 'Tinggi gambar terlalu besar, Max. ( {{ value }}px )',
        imageFormat: 'Format file tidak diizinkan, Hanya ( {{ value }} )',
    },
});
//end::Dropify
// Load Edit Profil Instansi
async function _loadEditProfilInstansi() {
    $("#cardProfilInstansi .card-footer button").attr("data-tab-active", tabActive);
    if (tabActive == "tab_general") {
        $("#form-general")[0].reset();
    } else if(tabActive == "tab_logo") {
        $("#form-logo")[0].reset(),
        _loadDropifyFile("", "#logo");
        // _loadDropifyFile("", "#kop_surat");
    }
    //Maps Marker Reset
    let markerLokasi = {
        lat: latitude,
        lng: longitude
    };
    if (tabActive == "tab_general") {
        await initializeMap();
        geocoder.geocode({ latLng: markerLokasi }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location),
                marker.setPosition(results[0].geometry.location),
                map.setZoom(7);
            }
        });
    }
    let target = document.querySelector("#cardProfilInstansi"), blockUi = new KTBlockUI(target, { message: messageBlockUi, zIndex: 9 });
    blockUi.block(), blockUi.destroy();
    $.ajax({
        url: base_url + "api/manage_profilinstansi/show",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            let instansi = data.row.instansi;
            if (tabActive == "tab_general") {
                if(instansi) {
                    $("#name").val(instansi.name),
                    $("#short_description").val(instansi.short_description),
                    $('#latitudeAddress').val(instansi.latitudeAddress),
                    $('#longitudeAddress').val(instansi.longitudeAddress);
                    if (instansi.office_address_coordinate) {
                        markerLokasi = {
                            lat: parseFloat(instansi.latitudeAddress),
                            lng: parseFloat(instansi.longitudeAddress)
                        };
                        geocoder.geocode({ latLng: markerLokasi }, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                map.setCenter(results[0].geometry.location),
                                marker.setPosition(results[0].geometry.location),
                                map.setZoom(18);
                            }
                        });
                    }

                    $("#address").val(instansi.office_address),
                    $("#phone_number").val(instansi.phone_number),
                    $("#email").val(instansi.email);
                }
            } else {
                if(instansi) {
                    _loadDropifyFile(instansi.logo_url,  "#logo");
                    // _loadDropifyFile(instansi.kop_surat_url, "#kop_surat");
                } else {
                    _loadDropifyFile('',  "#logo");
                    // _loadDropifyFile('', "#kop_surat");
                }
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log("Load data is error");
            blockUi.release(), blockUi.destroy();
        },
    });
};
//SMS Blasting Change Switch
$("#sms_blasting").change(function() {
    if(this.checked) {
        let waBlastingChecked = $("#whatsapp_blasting").is(':checked');
        if(waBlastingChecked) {
            toastr.error('Fitur blasting hanya bisa digunakan pada 1 jenis reminder blasting (SMS/ WhatsApp) saja!', 'Uuppss!', {"progressBar": true, "timeOut": 3000});
            $(this).prop('checked', false);
            return false;
        }
        $("#form-others .sms_blasting_false").show();
        $('#iGroup-sms_blasting .form-check-label').text('AKTIF');
    } else {
        $("#form-others .sms_blasting_false").hide();
        $('#iGroup-sms_blasting .form-check-label').text('TIDAK AKTIF');
    }
});
// Handle Button Reset / Batal Form Profil Instansi
$("#btn-resetFormProfil").on("click", function (e) {
    e.preventDefault();
    _loadEditProfil();
});
//Handle Enter Submit Form Edit Profil Instansi
$("#cardProfilInstansi input.form-enter").keyup(function (event) {
    if (event.keyCode == 13 || event.key === "Enter") {
        $("#btn-saveProfil").click();
    }
});
// Handle Button Save Form Profil Instansi
$("#btn-saveProfil").on("click", function (e) {
    e.preventDefault();
    $("#btn-saveProfil").attr("data-kt-indicator", "on").attr("disabled", true);
    if (tabActive == "tab_general") {
        let name = $("#name"), short_description = $("#short_description"),
            latitudeAddress = $("#latitudeAddress"), longitudeAddress = $("#longitudeAddress"),
            address = $("#address"), phone_number = $("#phone_number"), email = $("#email");

        if (name.val() == "") {
            toastr.error("Nama Instansi masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
            name.focus();
            $("#btn-saveProfil").removeAttr("data-kt-indicator").attr("disabled", false);
            return false;
        } if (short_description.val() == "") {
            toastr.error("Deskripsi Singkat Instansi masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
            short_description.focus();
            $("#btn-saveProfil").removeAttr("data-kt-indicator").attr("disabled", false);
            return false;
        } if (latitudeAddress.val() == "" && longitudeAddress.val() == "") {
            toastr.error("Titik lokasi alamat Kantor belum ditentukan ...", "Uuppss!", { progressBar: true, timeOut: 1500 });
            $('#searchMapAddress').focus();
            $("#btn-saveProfil").removeAttr('data-kt-indicator').attr('disabled', false);
            return false;
        } if (address.val() == "") {
            toastr.error("Alamat Kantor masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
            address.focus();
            $("#btn-saveProfil").removeAttr("data-kt-indicator").attr("disabled", false);
            return false;
        } if (phone_number.val() == '') {
            toastr.error('No. Telpon/ Hp Kantor masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            phone_number.focus();
            $('#btn-saveProfil').removeAttr('data-kt-indicator').attr('disabled', false);
            return false;
        } if (email.val() == '') {
            toastr.error('Email Kantor masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            email.focus();
            $('#btn-saveProfil').removeAttr('data-kt-indicator').attr('disabled', false);
            return false;
        }
    } else {
        let logo = $("#logo"),
            logo_preview = $("#iGroup-logo .dropify-preview .dropify-render").html();
            /* kop_surat = $("#kop_surat"),
            kop_surat_preview = $("#iGroup-kop_surat .dropify-preview .dropify-render").html(); */

        if (logo_preview == "") {
            toastr.error("Logo Instansi masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
            $("#iGroup-logo .dropify-wrapper").addClass("border-2 border-danger").stop().delay(1500).queue(function () {
                $(this).removeClass("border-2 border-danger");
            });
            logo.focus();
            $("#btn-saveProfil").removeAttr("data-kt-indicator").attr("disabled", false);
            return false;
        } /* if (kop_surat_preview == "") {
            toastr.error(
                "Kop Surat Instansi masih kosong...",
                "Uuppss!",
                { progressBar: true, timeOut: 1500 }
            );
            $("#iGroup-kop_surat .dropify-wrapper")
                .addClass("border-2 border-danger")
                .stop()
                .delay(1500)
                .queue(function () {
                    $(this).removeClass("border-2 border-danger");
                });
            kop_surat.focus();
            $("#btn-saveProfil")
                .removeAttr("data-kt-indicator")
                .attr("disabled", false);
            return false;
        } */
    }

    Swal.fire({
        title: "",
        text: "Simpan perubahan sekarang ?",
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.value) {
            let target = document.querySelector("#cardProfilInstansi"), blockUi = new KTBlockUI(target, {
                message: messageBlockUi,
                zIndex: 9,
            });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($("#form-general")[0]),
                ajax_url = base_url + "api/manage_profilinstansi/update";
            if (tabActive == "tab_general") {
                formData.append("form_type", "general");
            } else if (tabActive == "tab_logo") {
                formData = new FormData($("#form-logo")[0]);
                formData.append("form_type", "logo");
            } else {
                formData = new FormData($("#form-others")[0]);
                formData.append("form_type", "others");
            }
            $.ajax({
                url: ajax_url,
                headers: {
                    "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
                },
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (data) {
                    $("#btn-saveProfil").removeAttr("data-kt-indicator").attr("disabled", false);
                    blockUi.release(), blockUi.destroy();
                    if (data.status == true) {
                        Swal.fire({
                            title: "Success!",
                            html: data.message,
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: "Ooops!",
                            html: data.message,
                            icon: "warning",
                            allowOutsideClick: false,
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#btn-saveProfil").removeAttr("data-kt-indicator").attr("disabled", false);
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({
                        title: "Ooops!",
                        text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                        icon: "error",
                        allowOutsideClick: false,
                    });
                },
            });
        } else {
            $("#btn-saveProfil").removeAttr("data-kt-indicator").attr("disabled", false);
        }
    });
});
// Class Initialization
jQuery(document).ready(function () {
    _loadEditProfilInstansi();
    //If change tab Profil Instansi
    $("#tab-settings-profil a").on("click", function (e) {
        e.preventDefault();
        tabActive = $(this).attr("href").replace("#", "");
        _loadEditProfilInstansi();
    });
    $('#phone_number').mask('62099999999999');
    //Lock Space Username
	$('.no-space').on('keypress', function (e) {
		return e.which !== 32;
	});
    //Remove White Space in Input No-Space
    $('.no-space').bind('input change', function(){
        $(this).val(function(_, v){
            return v.replace(/\s+/g, '');
        });
    });
});
