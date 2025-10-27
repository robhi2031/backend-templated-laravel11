"use strict";
// Class Definition
//Message BlockUi
const messageBlockUi =
	'<div class="blockui-message bg-light text-gray-900"><span class="spinner-border spinner-border-sm align-middle text-success"></span> Mohon tunggu ...</div>';
//System INFO
const _loadSystemInfo = () => {
	$.ajax({
		url: base_url + "api/site_info",
		type: "GET",
		dataType: "JSON",
		success: function (data) {
			let siteInfo = data.row;
			$("#kt_body").attr(
				"style",
				`background-size: cover; background-position: unset; background-image: linear-gradient(0deg, rgb(2 18 13), #060606cc), url(` +siteInfo.login_bg_url+`);`
			),
			$("#hLogo-login").html(`<a href="` +base_url+ `auth" title="LOGIN - ` +siteInfo.short_name +`">
					<img src="` +siteInfo.login_logo_url+ `" class="mb-5 theme-light-show" height="52" alt="` +siteInfo.login_logo+ `">
					<img src="` +siteInfo.headbackend_logo_dark_url+ `" class="mb-5 theme-dark-show" height="52" alt="` +siteInfo.headbackend_logo_dark+ `">
			</a>`);
			$("#copyRight").html(siteInfo.copyright);
		},
		error: function (jqXHR, textStatus, errorThrown) {
			console.log("Load data is error");
		},
	});
};
// FORM CLASS LOGIN
const KTLogin = (function () {
	//SignIn Handle 1
	let _handleSignInForm = function () {
		$("#username").focus();
		//Handle Enter Submit
		$("#username").keyup(function (event) {
			if (event.keyCode == 13 || event.key === "Enter") {
				$("#btn-login1").click();
			}
		});
		// Handle submit button
		$("#btn-login1").on("click", function (e) {
			e.preventDefault();
			$("#btn-login1").attr("data-kt-indicator", "on").attr("disabled", true);
			let username = $("#username");
			if (username.val() == "") {
				toastr.error("Username atau Email masih kosong...", "Uuppss!", {
					progressBar: true,
					timeOut: 1500,
				});
				username.focus();
				$("#btn-login1").removeAttr("data-kt-indicator").attr("disabled", false);
				return false;
			}
			let target = document.querySelector("#kt_sign_in"), blockUi = new KTBlockUI(target, { message: messageBlockUi, zIndex: 9 });
			blockUi.block(), blockUi.destroy();
			let formData = new FormData($("#kt_sign_in_form")[0]);
			$.ajax({
				url: base_url + "api/auth/first_login",
				headers: {
					"X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
				},
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function (data) {
					$("#btn-login1").removeAttr("data-kt-indicator").attr("disabled", false);
					blockUi.release(), blockUi.destroy();
					if (data.status == true) {
						let tUserInfo = `<!--begin::Title-->
						<h4 class="text-gray-900  fw-500 mb-2">` +data.row.name+ `</h4>
						<!--end::Title-->
						<div class="btn-group">
							<button class="btn btn-bg-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="ki-duotone ki-profile-circle fs-3">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
								</i> ` +data.row.email+ `
							</button>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item dropdown-item-success dropdown-item-hover-success" href="` +base_url+`auth/logout"><i class="bi bi-person-x me-2 text-gray-900 fs-5"></i> Gunakan akun lain</a></li>
							</ul>
						</div>`;
						$('[name="hideMail"]').val(data.row.email),
						$("#hT-login2").html(tUserInfo),
						$("#fBody-login1").hide(),
						$("#fBody-login2").addClass("loginAnimated-fadeInRight").show(),
						$("#password").focus();
					} else {
						Swal.fire({
							title: "Ooops!",
							text: data.message,
							icon: "error",
							allowOutsideClick: false,
						}).then(function (result) {
							location.reload(true);
						});
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					$("#btn-login1").removeAttr("data-kt-indicator").attr("disabled", false);
					blockUi.release(), blockUi.destroy();
					Swal.fire({
						title: "Ooops!",
						text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!",
						icon: "error",
						allowOutsideClick: false,
					}).then(function (result) {
						location.reload(true);
					});
				},
			});
		});
	};
	//SignIn Handle 2
	let _handleSignIn2Form = function () {
		/* Show Hide Password */
		$("#showPass_checkbox").change(function (e) {
			e.preventDefault();
			if ($("#showPass_checkbox").is(":checked")) {
				$("#password").attr("type", "text");
			} else {
				$("#password").attr("type", "password");
			}
		});
		//Handle Enter Submit
		$("#password").keyup(function (event) {
			if (event.keyCode == 13 || event.key === "Enter") {
				$("#btn-login2").click();
			}
		});
		// Handle submit button
		$("#btn-login2").on("click", function (e) {
			e.preventDefault();
			$("#btn-login2").attr("data-kt-indicator", "on").attr("disabled", true);
			let password = $("#password");
			if (password.val() == "") {
				toastr.error("Password masih kosong...", "Uuppss!", {
					progressBar: true,
					timeOut: 1500,
				});
				password.focus();
				$("#btn-login2").removeAttr("data-kt-indicator").attr("disabled", false);
				return false;
			}
			let target = document.querySelector("#kt_sign_in"), blockUi = new KTBlockUI(target, { message: messageBlockUi, zIndex: 9 });
			blockUi.block(), blockUi.destroy();
			let formData = new FormData($("#kt_sign_in_form")[0]);
			$.ajax({
				url: base_url + "api/auth/second_login",
				headers: {
					"X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
				},
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function (data) {
					$("#btn-login2").removeAttr("data-kt-indicator").attr("disabled", false);
					blockUi.release(), blockUi.destroy();
					if (data.status == true) {
						Swal.fire({
							title: "Success!",
							text: "Login berhasil, sistem akan mengarahkan anda ke halaman dashboard dalam beberapa detik...",
							icon: "success",
							timer: 3000,
							timerProgressBar: true,
							showConfirmButton: false,
							allowOutsideClick: false,
						}).then(function (result) {
							$("#kt_sign_in").hide();
							let nextUrl = data.row.last_visited_url ? data.row.last_visited_url : base_url;
							window.location = nextUrl;
						});
					} else {
						Swal.fire({
							title: "Ooops!",
							text: data.message,
							icon: "error",
							allowOutsideClick: false,
						}).then(function (result) {
							if (data.row.error_code == "PASSWORD_NOT_VALID") {
								password.val("").focus();
							} else {
								location.reload(true);
							}
						});
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					$("#btn-login2").removeAttr("data-kt-indicator").attr("disabled", false);
					blockUi.release(), blockUi.destroy();
					Swal.fire({
						title: "Ooops!",
						text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!",
						icon: "error",
						allowOutsideClick: false,
					}).then(function (result) {
						location.reload(true);
					});
				},
			});
		});
		/*/ Handle forgot button
		$('#kt_login_forgot').on('click', function (e) {
			e.preventDefault();
			//_showForm('forgot');
			Swal.fire({
				title: "Warning!",
				html: 'Mohon maaf fitur ini sedang tahap pengembangan. Agar dapat login ke sistem, silahkan lakukan konfirmasi akun user ke pihak Admin PIC Aplikasi.',
				icon: "warning",
				allowOutsideClick: false
			})
		});*/
	};
	/*var _handleForgotForm = function(e) {
			// Handle submit button
			$('#kt_login_forgot_submit').on('click', function (e) {
					e.preventDefault();
			});
			// Handle cancel button
			$('#kt_login_forgot_cancel').on('click', function (e) {
					e.preventDefault();
					_showForm('signin2');
			});
	}*/
	// Public Functions
	return {
		// public functions
		init: function () {
			_handleSignInForm();
			_handleSignIn2Form();
			//_handleForgotForm();
		},
	};
})();
// Class Initialization
jQuery(document).ready(function () {
	_loadSystemInfo(), KTLogin.init();
});
