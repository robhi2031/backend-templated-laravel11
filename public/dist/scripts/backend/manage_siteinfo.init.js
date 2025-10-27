"use strict";
// Class Definition
let tabActive = "tab_general";
//Keyword Select2
$("#keyword").select2({
    dropdownAutoWidth: true,
    tags: true,
    maximumSelectionLength: 10,
    placeholder: "Isi keyword/ kata kunci situs ...",
    tokenSeparators: [","],
    width: "100%",
    language: { noResults: () => "Gunakan tanda koma (,) sebagai pemisah tag" },
});
//CopyRight Input
$("#copyright").summernote({
    placeholder: "Isi copyright situs ...",
    toolbar: [
        ["style", ["bold", "italic", "underline"]],
        ["insert", ["link"]],
        ["view", ["codeview"]],
    ],
    height: 100,
    minHeight: null,
    maxHeight: null,
    dialogsInBody: false,
    focus: false,
    popatmouse: false,
    lang: "id-ID",
});
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

const _loadEditSiteInfo = () => {
    $("#cardSiteInfo .card-footer button").attr("data-tab-active", tabActive);
    if (tabActive == "tab_general") {
        $("#form-general")[0].reset(),
        $("#keyword").html("").trigger("change"),
        $("#copyright").summernote("code", "");
    } else {
        $("#form-logoAndOthers")[0].reset(),
        _loadDropifyFile("", "#login_logo"),
        _loadDropifyFile("", "#login_bg"),
        _loadDropifyFile("", "#headbackend_logo"),
        _loadDropifyFile("", "#headbackend_logo_dark"),
        _loadDropifyFile("", "#headbackend_icon"),
        _loadDropifyFile("", "#headbackend_icon_dark");
    }

    let target = document.querySelector("#cardSiteInfo"), blockUi = new KTBlockUI(target, { message: messageBlockUi, zIndex: 9 });
    blockUi.block(), blockUi.destroy();
    $.ajax({
        url: base_url + "api/site_info",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            if (tabActive == "tab_general") {
                $("#name").val(data.row.name),
                $("#short_name").val(data.row.short_name),
                $("#description").val(data.row.description);
                //Keyword System
                let selected = "", i;
                for (i = 0; i < data.row.keyword_explode.length; i++) {
                    selected += '<option value="' +data.row.keyword_explode[i]+ '" selected>' +data.row.keyword_explode[i]+ "</option>";
                }
                $("#keyword").html(selected).trigger("change");
                //Summernote CopyRight
                let copyright = data.row.copyright;
                $("#copyright").summernote("code", copyright);
            } else {
                _loadDropifyFile(data.row.login_logo_url, "#login_logo"),
                _loadDropifyFile(data.row.login_bg_url, "#login_bg"),
                _loadDropifyFile(data.row.headbackend_logo_url, "#headbackend_logo"),
                _loadDropifyFile(data.row.headbackend_logo_dark_url, "#headbackend_logo_dark"),
                _loadDropifyFile(data.row.headbackend_icon_url, "#headbackend_icon"),
                _loadDropifyFile(data.row.headbackend_icon_dark_url, "#headbackend_icon_dark");
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log("Load data is error");
            blockUi.release(), blockUi.destroy();
        },
    });
};
// Handle Button Reset / Batal Form Site Info
$("#btn-resetFormSiteInfo").on("click", function (e) {
    e.preventDefault();
    _loadEditSiteInfo();
});
//Handle Enter Submit Form Edit Site Info
$("#cardSiteInfo input").keyup(function (event) {
    if (event.keyCode == 13 || event.key === "Enter") {
        $("#btn-saveSiteInfo").click();
    }
});
// Handle Button Save Form Site Info
$("#btn-saveSiteInfo").on("click", function (e) {
    e.preventDefault();
    $("#btn-saveSiteInfo")
        .attr("data-kt-indicator", "on")
        .attr("disabled", true);
    if (tabActive == "tab_general") {
        let name = $("#name"),
            short_name = $("#short_name"),
            description = $("#description"),
            keyword = $("#keyword"),
            copyright = $("#copyright");

        if (name.val() == "") {
            toastr.error("Nama situs masih kosong...", "Uuppss!", {
                progressBar: true,
                timeOut: 1500,
            });
            name.focus();
            $("#btn-saveSiteInfo")
                .removeAttr("data-kt-indicator")
                .attr("disabled", false);
            return false;
        } if (short_name.val() == "") {
            toastr.error(
                "Nama alias/ nama pendek situs masih kosong...",
                "Uuppss!",
                { progressBar: true, timeOut: 1500 }
            );
            short_name.focus();
            $("#btn-saveSiteInfo")
                .removeAttr("data-kt-indicator")
                .attr("disabled", false);
            return false;
        } if (description.val() == "") {
            toastr.error("Deskripsi situs masih kosong...", "Uuppss!", {
                progressBar: true,
                timeOut: 1500,
            });
            description.focus();
            $("#btn-saveSiteInfo")
                .removeAttr("data-kt-indicator")
                .attr("disabled", false);
            return false;
        } if (keyword.val() == "" || keyword.val() == null) {
            toastr.error(
                "Keyword/ kata kunci situs masih kosong...",
                "Uuppss!",
                { progressBar: true, timeOut: 1500 }
            );
            keyword.focus().select2("open");
            $("#btn-saveSiteInfo")
                .removeAttr("data-kt-indicator")
                .attr("disabled", false);
            return false;
        } if (copyright.summernote("isEmpty")) {
            toastr.error("Copyright situs masih kosong...", "Uuppss!", {
                progressBar: true,
                timeOut: 1500,
            });
            copyright.summernote("focus");
            $("#btn-saveSiteInfo")
                .removeAttr("data-kt-indicator")
                .attr("disabled", false);
            return false;
        }
    } else {
        let login_logo = $("#login_logo"),
            login_logo_preview = $("#iGroup-login_logo .dropify-preview .dropify-render").html(),
            login_bg = $("#login_bg"),
            login_bg_preview = $("#iGroup-login_bg .dropify-preview .dropify-render").html(),
            headbackend_logo = $("#headbackend_logo"),
            headbackend_logo_preview = $("#iGroup-headbackend_logo .dropify-preview .dropify-render").html(),
            headbackend_logo_dark = $("#headbackend_logo_dark"),
            headbackend_logo_dark_preview = $("#iGroup-headbackend_logo_dark .dropify-preview .dropify-render").html(),
            headbackend_icon = $("#headbackend_icon"),
            headbackend_icon_preview = $("#iGroup-headbackend_icon .dropify-preview .dropify-render").html(),
            headbackend_icon_dark = $("#headbackend_icon_dark"),
            headbackend_icon_dark_preview = $("#iGroup-headbackend_icon_dark .dropify-preview .dropify-render").html();

        if (login_logo_preview == "") {
            toastr.error("Logo login masih kosong...", "Uuppss!", {
                progressBar: true,
                timeOut: 1500,
            });
            $("#iGroup-login_logo .dropify-wrapper")
                .addClass("border-2 border-danger")
                .stop()
                .delay(1500)
                .queue(function () {
                    $(this).removeClass("border-2 border-danger");
                });
            login_logo.focus();
            $("#btn-saveSiteInfo")
                .removeAttr("data-kt-indicator")
                .attr("disabled", false);
            return false;
        } if (login_bg_preview == "") {
            toastr.error("Gambar background login masih kosong...", "Uuppss!", {
                progressBar: true,
                timeOut: 1500,
            });
            $("#iGroup-login_bg .dropify-wrapper")
                .addClass("border-2 border-danger")
                .stop()
                .delay(1500)
                .queue(function () {
                    $(this).removeClass("border-2 border-danger");
                });
            login_bg.focus();
            $("#btn-saveSiteInfo")
                .removeAttr("data-kt-indicator")
                .attr("disabled", false);
            return false;
        } if (headbackend_logo_preview == "") {
            toastr.error("Logo backend light mode masih kosong...", "Uuppss!", {
                progressBar: true,
                timeOut: 1500,
            });
            $("#iGroup-headbackend_logo .dropify-wrapper")
                .addClass("border-2 border-danger")
                .stop()
                .delay(1500)
                .queue(function () {
                    $(this).removeClass("border-2 border-danger");
                });
            headbackend_logo.focus();
            $("#btn-saveSiteInfo")
                .removeAttr("data-kt-indicator")
                .attr("disabled", false);
            return false;
        } if (headbackend_logo_dark_preview == "") {
            toastr.error("Logo backend dark mode masih kosong...", "Uuppss!", {
                progressBar: true,
                timeOut: 1500,
            });
            $("#iGroup-headbackend_logo_dark .dropify-wrapper")
                .addClass("border-2 border-danger")
                .stop()
                .delay(1500)
                .queue(function () {
                    $(this).removeClass("border-2 border-danger");
                });
            headbackend_logo_dark.focus();
            $("#btn-saveSiteInfo")
                .removeAttr("data-kt-indicator")
                .attr("disabled", false);
            return false;
        } if (headbackend_icon_preview == "") {
            toastr.error(
                "Logo icon backend light mode masih kosong...",
                "Uuppss!",
                { progressBar: true, timeOut: 1500 }
            );
            $("#iGroup-headbackend_icon .dropify-wrapper")
                .addClass("border-2 border-danger")
                .stop()
                .delay(1500)
                .queue(function () {
                    $(this).removeClass("border-2 border-danger");
                });
            headbackend_icon.focus();
            $("#btn-saveSiteInfo")
                .removeAttr("data-kt-indicator")
                .attr("disabled", false);
            return false;
        } if (headbackend_icon_dark_preview == "") {
            toastr.error(
                "Logo icon backend dark mode masih kosong...",
                "Uuppss!",
                { progressBar: true, timeOut: 1500 }
            );
            $("#iGroup-headbackend_icon_dark .dropify-wrapper")
                .addClass("border-2 border-danger")
                .stop()
                .delay(1500)
                .queue(function () {
                    $(this).removeClass("border-2 border-danger");
                });
            headbackend_icon_dark.focus();
            $("#btn-saveSiteInfo")
                .removeAttr("data-kt-indicator")
                .attr("disabled", false);
            return false;
        }
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
            let target = document.querySelector("#cardSiteInfo"), blockUi = new KTBlockUI(target, {
                message: messageBlockUi,
                zIndex: 9,
            });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($("#form-general")[0]),
                ajax_url = base_url + "api/manage_siteinfo/update";
            if (tabActive == "tab_general") {
                let get_copyright = formData.get("copyright");
                formData.set(
                    "copyright",
                    encodeURIComponent(encodeURIComponent(get_copyright))
                );
                formData.append("form_type", "general");
            } else {
                formData = new FormData($("#form-logoAndOthers")[0]);
                formData.append("form_type", "logo_and_others");
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
                    $("#btn-saveSiteInfo")
                        .removeAttr("data-kt-indicator")
                        .attr("disabled", false);
                    blockUi.release(), blockUi.destroy();
                    if (data.status == true) {
                        Swal.fire({
                            title: "Success!",
                            text: data.message,
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: "Ooops!",
                            text: data.message,
                            icon: "warning",
                            allowOutsideClick: false,
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $("#btn-saveSiteInfo")
                        .removeAttr("data-kt-indicator")
                        .attr("disabled", false);
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
            $("#btn-saveSiteInfo")
                .removeAttr("data-kt-indicator")
                .attr("disabled", false);
        }
    });
});
// Class Initialization
jQuery(document).ready(function () {
    _loadEditSiteInfo();
    //If change tab site info
    $("#tab-settings-siteInfo a").on("click", function (e) {
        e.preventDefault();
        tabActive = $(this).attr("href").replace("#", "");
        _loadEditSiteInfo();
    });
});
