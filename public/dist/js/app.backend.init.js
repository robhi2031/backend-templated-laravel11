"use strict";
// Class Definition
//Message BlockUi
const messageBlockUi = '<div class="blockui-message bg-light text-gray-900"><span class="spinner-border spinner-border-sm align-middle text-success"></span> Mohon tunggu ...</div>';
//Validate Email
const validateEmail = (email) => {
    let re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
//To Capitalizes for Word
const strToCapitalize = (phrase) => {
    return phrase
        .toLowerCase()
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
};
//System INFO
const _loadSystemInfo = () => {
	$.ajax({
        url: base_url+ "api/site_info",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            let siteInfo = data.row;
            $('#kt_app_sidebar_logo a').html(`<img alt="Logo" src="` +siteInfo.headbackend_logo_dark_url+ `" class="h-30px app-sidebar-logo-default" />
                <img alt="Logo" src="` +siteInfo.headbackend_icon_dark_url+ `" class="h-30px app-sidebar-logo-minimize" />`);
            $('#mobileHeadLogo').html(`<img alt="Logo" src="` +siteInfo.headbackend_logo_url+ `" class="h-20px theme-light-show" />
                <img alt="Logo" src="` +siteInfo.headbackend_logo_dark_url+ `" class="h-20px theme-dark-show" />`);
            $('#copyRight').html(siteInfo.copyright);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
//User INFO
const _loadUserInfo = () => {
	$.ajax({
        url: base_url+ "api/user_info",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            let userInfo = data.row;
            let thumbHeader;
            let userThumbHeader = `<img src="` +userInfo.url_thumb+ `" class="rounded-3" alt="avatar-user" />`;
            let userSymbol = `<div class="symbol-label fs-1 fw-bold ` +userInfo.symbol_color+ `">` +userInfo.text_symbol+ `</div>`;
            if(userInfo.thumb==null || userInfo.thumb=='') {
                thumbHeader = userSymbol;
            } else {
                thumbHeader = userThumbHeader;
            }
            $('#kt_header_user_menu_toggle .avatar-header').html(thumbHeader);
            $('#navbarUserInfo').html(`<div class="fw-bold d-flex align-items-center fs-5 text-break">
                ` +userInfo.name+ `
            </div>
            <a href="javascript:void(0);" class="fw-semibold text-muted text-hover-success fs-7 text-break"> ` +userInfo.email+ ` </a>`);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
//Upload Image to Local Server with Summernote JS
const _uploadFile_editor = function(image, idCustom) {
	let data = new FormData();
	data.append("image", image);
	$.ajax({
		url: base_url+ "api/upload_imgeditor",
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
		data: data,
		type: "POST",
		cache: false,
        contentType: false,
        processData: false,
        dataType: "JSON",
		success: function(data){
			if(data.status){
				if(idCustom){
					$(idCustom).summernote("insertImage", data.row.url_img);
				}else{
					$('.summernote').summernote("insertImage", data.row.url_img);
				}
				//var image = $('<img>').attr('src', url);
				//$('#summernote').summernote("insertNode", url);
			}else{
				Swal.fire({
					title: "Ooops!",
					html: data.message,
					icon: "warning", allowOutsideClick: false
				});
			}
		}, error: function (jqXHR, textStatus, errorThrown, data) {
			console.log('Error upload images to text editor');
			toastr.error(errorThrown+ ', <br />' +jqXHR.responseJSON.errors.image[0], 'Uuppss!', {"progressBar": true, "timeOut": 1500});
		}
	});
};
// Class Initialization
jQuery(document).ready(function() {
    _loadSystemInfo(), _loadUserInfo();
});
