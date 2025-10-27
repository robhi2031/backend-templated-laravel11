"use strict";
//Class Definition
let save_method, table;
//Load Datatables Users
const _loadDtUsers = () => {
    table = $('#dt-users').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url+ 'api/manage_users/show',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            type: 'GET',
        },
        destroy: true,
        draw: true,
        deferRender: true,
        responsive: false,
        autoWidth: false,
        LengthChange: true,
        paginate: true,
        pageResize: true,
        columns: [
            // { data: 'DT_RowIndex', name: 'DT_RowIndex', width: "5%", className: "align-top text-center border px-2", searchable: false },
            { data: 'action', name: 'action', width: "15%", className: "align-top text-center border px-2", orderable: false, searchable: false },
            { data: 'role', name: 'role', width: "1%", className: "align-top border px-2", visible: false, orderable: false },
            { data: 'name', name: 'name', width: "29%", className: "align-top border px-2" },
            { data: 'email', name: 'email', width: "15%", className: "align-top border px-2" },
            { data: 'phone_number', name: 'phone_number', width: "15%", className: "align-top border px-2" },
            { data: 'is_active', name: 'is_active', width: "5%", className: "align-top text-center border px-2", searchable: false },
            { data: 'last_login', name: 'last_login', width: "20%", className: "align-top border px-2", searchable: false },
        ],
        oLanguage: {
            sEmptyTable: "Tidak ada Data yang dapat ditampilkan..",
            sInfo: "Menampilkan _START_ s/d _END_ dari _TOTAL_",
            sInfoEmpty: "Menampilkan 0 - 0 dari 0 entri.",
            sInfoFiltered: "",
            sProcessing: `<div class="d-flex justify-content-center align-items-center"><span class="spinner-border align-middle me-3"></span> Mohon Tunggu...</div>`,
            sZeroRecords: "Tidak ada Data yang dapat ditampilkan..",
            sLengthMenu: `<select class="mb-2 show-tick form-select-solid" data-width="fit" data-style="btn-sm btn-success" data-container="body">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="40">40</option>
                <option value="50">50</option>
                <option value="-1">Semua</option>
            </select>`,
            oPaginate: {
                sPrevious: "Sebelumnya",
                sNext: "Selanjutnya",
            },
        },
        //"dom": "<'row'<'col-sm-6 d-flex align-items-center justify-conten-start'l><'col-sm-6 d-flex align-items-center justify-content-end'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
        fnDrawCallback: function (settings, display) {
            //Grouping
            let api = this.api();
            let rows = api.rows({
                page: 'current'
            }).nodes();
            let last = null;
            api.column(1, {
                page: 'current'
            }).data().each(function (group, i) {
                if (last !== group) {
                    $(rows).eq(i).before(
                        '<tr class="group fw-bold text-uppercase"><td class="px-3 border bg-secondary" colspan="6"><i class="bi bi-person-badge fs-3 me-1 text-dark"></i> ' + group + '</td></tr>'
                    );
                    last = group;
                }
            });
            //Search Table
            $("#search-dtUsers").on("keyup", function (event) {
                if (event.keyCode == 13 || event.key === "Enter") {
                    table.search(this.value).draw();
                } if ($(this).val().length > 0) {
                    $("#clear-searchDtUsers").show();
                } else {
                    table.search(this.value).draw();
                    $("#clear-searchDtUsers").hide();
                }
            });
            //Clear Search Table
            $("#clear-searchDtUsers").on("click", function () {
                $("#search-dtUsers").val(""),
                table.search("").draw(),
                $("#clear-searchDtUsers").hide();
            });
            //Custom Table
            $("#dt-users_length select").selectpicker(),
            $('[data-bs-toggle="tooltip"]').tooltip({
                trigger: "hover"
            }).on("click", function () {
                $('.tooltip.show').remove();
            });
            //Image PopUp
            $('.image-popup').magnificPopup({
                type: 'image', closeOnContentClick: true, closeBtnInside: false, fixedContentPos: true,
                image: {
                    verticalFit: true
                }
            });
            //Change Check Active Switch
            $('#dt-users .is-public input[type="checkbox"]').change(function () {
                _updateStatus(this);
            });
        },
    });
    $("#dt-users").css("width", "100%"),
    $("#search-dtUsers").val(""),
    $("#clear-searchDtUsers").hide();
}
//Load Selectpicker Role
const _loadSelectpicker_role = () => {
    $.ajax({
        url: base_url+ "api/manage_users/show",
        type: "GET",
        dataType: "JSON",
        data: {
            is_selectrow: true,
            is_roles: true
        },
        success: function (data) {
            let output = '';
            let i;
            for (i = 0; i < data.row.length; i++) {
                output += '<option value="' + data.row[i].id + '">' + data.row[i].name + '</option>';
            }
            $('#role').html(output).selectpicker('refresh').selectpicker('val', '');
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
//Close Content Card by Open Method
const _closeCard = (card) => {
    if(card=='form_user') {
        save_method = '';
        _clearFormUser(), $('#card-formUser .card-header .card-title').html('');
        $('#card-formUser').hide();
    }
    $('#card-dtUsers').show();
}
//Clear Form User
const _clearFormUser = () => {
    $('#avatar_remove').val(1),
    $('#iGroup-userThumb .image-input-outline').addClass('image-input-empty'),
    $('#iGroup-userThumb .image-input-outline .image-input-wrapper').attr('style', 'background-image: none;'),
    _loadFotoUser('', ''), $('#avatar_text').val(""), $('[name="oldRole_id"]').val("");
    $('#iGroup-terminal').hide(), $('#iGroup-cboSdm').hide(), $('#iGroup-name').show(),
    $('#form-user .input-readonly').attr('readonly', false);
    if (save_method == "" || save_method == "add_user") {
        $("#form-user")[0].reset(), $('[name="id"]').val(""),
        $('#role').selectpicker('refresh').selectpicker('val', '');
        $('.password-group').show(), $('#iGroup-isActive').hide();
        // Handle Button Cancel
        $('#btn-cancelUserThumb').on('click', function (e) {
            e.preventDefault();
            _loadFotoUser('', '');
        });
    } else {
        let idp = $('[name="id"]').val();
        _editUser(idp);
    }
}
//Add User
const _addUser = () => {
    save_method = "add_user";
    _clearFormUser(),
    $("#card-formUser .card-header .card-title").html(
        `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-window-plus fs-2 text-gray-900 me-2"></i>Form Tambah User</h3>`
    ),
    $("#card-dtUsers").hide(), $("#card-formUser").show();
}
//Edit User
const _editUser = (idp) => {
    save_method = "update_user";
    $('#form-user')[0].reset(), $('.password-group').hide(), $('#iGroup-isActive').show();
    let target = document.querySelector("#card-formUser"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    //Ajax load from ajax
    $.ajax({
        url: base_url+ 'api/manage_users/show',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        data: {
            idp,
        },
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            if (data.status == true) {
                let userInfo = data.row;
                $('[name="id"]').val(userInfo.id),
                _loadFotoUser(userInfo.thumb, userInfo.url_thumb),
                $('[name="oldRole_id"]').val(userInfo.role_id),
                $('#role').selectpicker('refresh').selectpicker('val', userInfo.role_id.toString());
                $('#name').val(userInfo.name);
                $('#username').val(userInfo.username),
                $('#email').val(userInfo.email),
                $('#phone_number').val(userInfo.phone_number);
                if (userInfo.is_active == 'Y') {
                    $('#is_active').prop('checked', true),
                    $('#iGroup-isActive .form-check-label').text('AKTIF');
                } else {
                    $('#is_active').prop('checked', false),
                    $('#iGroup-isActive .form-check-label').text('TIDAK AKTIF');
                }
                $("#card-formUser .card-header .card-title").html(
                    `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-pencil-square fs-2 text-gray-900 me-2"></i>Form Edit User</h3>`
                ),
                $("#card-dtUsers").hide(), $("#card-formUser").show();
            } else {
                Swal.fire({title: "Ooops!", text: data.message, icon: "warning", allowOutsideClick: false});
            }
        }, complete: function(data) {
            // Handle Button Cancel
            $('#btn-cancelUserThumb').on('click', function (e) {
                e.preventDefault();
                _loadFotoUser(data.responseJSON.row.thumb, data.responseJSON.row.url_thumb);
            });
        }, error: function (jqXHR, textStatus, errorThrown) {
            blockUi.release(), blockUi.destroy();
            console.log("load data is error!");
            Swal.fire({
                title: "Ooops!",
                text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                icon: "error",
                allowOutsideClick: false,
            });
        },
    });
}
//Change Avatar Selected
$("#avatar").change(function (e) {
    e.preventDefault();
    let file = $(this).val();
    if(typeof file !== 'undefined' && file !== null && file !== '') {
        $('#avatar_text').val('');
    }
});
//Load Foto User Profile
const _loadFotoUser = (foto, url_foto) => {
    $('#iGroup-userThumb .image-input-outline').removeClass('image-input-changed image-input-empty'),
    $('#avatar_remove').val(0);
    if(!foto){
        $('#avatar_remove').val(1),
        $('#iGroup-userThumb .image-input-outline').addClass('image-input-empty'),
        $('#iGroup-userThumb .image-input-outline .image-input-wrapper').attr('style', 'background-image: none;');
    } else {
        $('#iGroup-userThumb .image-input-outline .image-input-wrapper').attr('style', `background-image: url('` +url_foto+ `');`);
    }
}
//Save User by Enter
$("#form-user input").keyup(function (event) {
    if (event.keyCode == 13 || event.key === "Enter") {
        $("#btn-save").click();
    }
});
//Save User Form
$("#btn-save").on("click", function (e) {
    e.preventDefault();
    $("#btn-save").attr('data-kt-indicator', 'on').attr('disabled', true);
    let userThumb = $('#iGroup-userThumb .image-input-wrapper'),
        role = $("#role"),
        name = $("#name"),
        username = $("#username"),
        email = $("#email"),
        phone_number = $("#phone_number");

    if (role.val() == '') {
        toastr.error('Role user masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-role button').removeClass('btn-outline-success btn-active-light-success').addClass('btn-outline-danger btn-active-light-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('btn-outline-danger btn-active-light-danger').addClass('btn-outline-success btn-active-light-success');
        });
        role.focus();
        $('#btn-save').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (name.val() == "") {
        toastr.error("Nama masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        name.focus();
        $("#btn-save").removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (username.val() == "") {
        toastr.error("Username masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        username.focus();
        $("#btn-save").removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (email.val() == "") {
        toastr.error("Email masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        email.focus();
        $("#btn-save").removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (!validateEmail(email.val())) {
        toastr.error('Email tidak valid!  contoh: ardi.jeg@gmail.com ...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        email.focus();
        $('#btn-save').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (phone_number.val() == "") {
        toastr.error("No. Telpon/ Hp masih kosong...", "Uuppss!", { progressBar: true, timeOut: 1500 });
        phone_number.focus();
        $("#btn-save").removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if (userThumb.attr('style')=='' || userThumb.attr('style')=='background-image: none;' || userThumb.attr('style')=='background-image: url();') {
        toastr.error('Foto User masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-userThumb .image-input').addClass('border border-2 border-danger').stop().delay(1500).queue(function () {
			$(this).removeClass('border border-2 border-danger');
		});
        $('#avatar').focus();
        $('#btn-save').removeAttr('data-kt-indicator').attr('disabled', false);
        return false;
    } if(save_method == 'add_user') {
        let pass = $('#pass_user'), repass = $('#repass_user');
        if (pass.val() == '') {
            toastr.error('Password masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            pass.focus();
            $('#btn-save').removeAttr('data-kt-indicator').attr('disabled', false);
            return false;
        } if (pass.val().length < 6) {
            toastr.error('Password tidak boleh kurang dari 6 karakter...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            pass.focus();
            $('#btn-save').removeAttr('data-kt-indicator').attr('disabled', false);
            return false;
        } if (repass.val() == '') {
            toastr.error('Ulangi Password...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            repass.focus();
            $('#btn-save').removeAttr('data-kt-indicator').attr('disabled', false);
            return false;
        } if (pass.val() != repass.val()) {
            toastr.error('Ulangi Password harus sama...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            repass.focus();
            $('#btn-save').removeAttr('data-kt-indicator').attr('disabled', false);
            return false;
        }
    }

    let textConfirmSave = "Simpan perubahan data sekarang ?";
    if (save_method == "add_user") {
        textConfirmSave = "Tambahkan data sekarang ?";
    }

    Swal.fire({
        title: "",
        text: textConfirmSave,
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.value) {
            let target = document.querySelector("#card-formUser"), blockUi = new KTBlockUI(target, { message: messageBlockUi, zIndex: 9 });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($("#form-user")[0]), ajax_url = base_url+ "api/manage_users/store";
            if(save_method == 'update_user') {
                ajax_url = base_url+ "api/manage_users/update";
            }
            $.ajax({
                url: ajax_url,
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (data) {
                    $("#btn-save").removeAttr('data-kt-indicator').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    if (data.status == true) {
                        Swal.fire({
                            title: "Success!",
                            text: data.message,
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            _closeCard('form_user'), $('#dt-users').DataTable().ajax.reload( null, false );
                        });
                    } else {
                        Swal.fire({
                            title: "Ooops!",
                            html: data.message,
                            icon: "warning",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            if (data.row.error_code == "sdm_available") {
                                cbo_sdm.focus().select2('open');
                            } if (data.row.error_code == "username_available") {
                                username.focus();
                            } if (data.row.error_code == "email_available") {
                                email.focus();
                            }
                        });
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $("#btn-save").removeAttr('data-kt-indicator').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({
                        title: "Ooops!",
                        text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                        icon: "error",
                        allowOutsideClick: false,
                    });
                }
            });
        } else {
            $("#btn-save").removeAttr('data-kt-indicator').attr('disabled', false);
        }
    });
});
//Update Status Data User
const _updateStatus = (obj) => {
    let textLbl = 'Nonaktifkan',
        idp = $(obj).attr('row-id'),
        value = 'N';
    if (obj.checked) {
        textLbl = 'Aktifkan';
        value = 'Y';
    }
    let textSwal = textLbl+ ' user sekarang ?';
    Swal.fire({
        title: "",
        html: textSwal,
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak, Batalkan!"
    }).then(result => {
        if (result.value) {
            let target = document.querySelector('#card-dtUsers'), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            // Load Ajax
            $.ajax({
                url: base_url+ "api/manage_users/update",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                dataType: "JSON",
                data: {
                    update_status: true,
                    idp, value
                }, success: function (data) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Success!", html: data.message, icon: "success", allowOutsideClick: false }).then(function (result) {
                        $('#dt-users').DataTable().ajax.reload( null, false );
                    });
                }, error: function (jqXHR, textStatus, errorThrown) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false }).then(function (result) {
                        console.log("Update data is error!");
                        $('#dt-users').DataTable().ajax.reload( null, false );
                    });
                }
            });
        } else {
            if (obj.checked) {
                $(obj).prop('checked', false);
            } else {
                $(obj).prop('checked', true);
            }
        }
    });
}
//Delete Data User
const _deleteUser = (idp) => {
    Swal.fire({
        title: "",
        html: 'Yakin hapus data user?',
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Yakin",
        cancelButtonText: "Tidak, Batalkan!"
    }).then(result => {
        if (result.value) {
            let target = document.querySelector('#card-dtUsers'), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            // Load Ajax
            $.ajax({
                url: base_url+ "api/manage_users/delete",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                dataType: "JSON",
                data: {
                    idp
                }, success: function (data) {
                    blockUi.release(), blockUi.destroy();
                    if (data.status == true) {
                        Swal.fire({ title: "Success!", html: data.message, icon: "success", allowOutsideClick: false }).then(function (result) {
                            $('#dt-users').DataTable().ajax.reload( null, false );
                        });
                    } else {
                        Swal.fire({
                            title: "Ooops!",
                            text: data.message,
                            icon: "warning",
                            allowOutsideClick: false,
                        });
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false }).then(function (result) {
                        console.log("Update data is error!");
                    });
                }
            });
        }
    });
}
//Reset User Password
const _resetUserPass = (idp) => {
    Swal.fire({
        title: "",
        html: 'Yakin ingin melakukan <strong>Reset Password</strong> user?',
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Yakin",
        cancelButtonText: "Tidak, Batalkan!"
    }).then(result => {
        if (result.value) {
            let target = document.querySelector('#card-dtUsers'), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            // Load Ajax
            $.ajax({
                url: base_url+ "api/manage_users/update",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                dataType: "JSON",
                data: {
                    reset_pass: true,
                    idp
                }, success: function (data) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Success!", html: data.message, icon: "success", allowOutsideClick: false });
                }, error: function (jqXHR, textStatus, errorThrown) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false }).then(function (result) {
                        console.log("Update data is error!");
                    });
                }
            });
        }
    });
}
//Class Initialization
jQuery(document).ready(function() {
    _loadDtUsers(), _loadSelectpicker_role();
    //Change Check Switch
    $("#is_active").change(function() {
        if(this.checked) {
            $('#iGroup-isActive .form-check-label').text('AKTIF');
        }else{
            $('#iGroup-isActive .form-check-label').text('TIDAK AKTIF');
        }
    });
    //Mask Custom
    $('#phone_number').mask('6290000000000');
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
    /* [ Show pass ] */
    let showPass = 0;
    $(document).on("mouseenter mouseleave", ".btn-showPass", function (e) {
        if (e.type == "mouseenter") {
            $('.password').attr('type', 'text');
            $('.btn-showPass i').removeClass('las la-eye-slash').addClass('las la-eye');
            $('.btn-showPass').attr('title', 'Tampilkan password');
            showPass = 1;
        } else {
            $('.password').attr('type', 'password');
            $('.btn-showPass i').removeClass('las la-eye').addClass('las la-eye-slash');
            $('.btn-showPass').attr('title', 'Sembunyikan password');
            showPass = 0;
        }
    });
});
