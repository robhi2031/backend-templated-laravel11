"use strict";
// Class Definition
//Message BlockUi
const messageBlockUi = '<div class="blockui-message bg-light text-dark"><span class="spinner-border text-primary"></span> Please wait ...</div>';
//Validate Email
const validateEmail = (email) => {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
//System INFO
const _loadSystemInfo = () => {
	$.ajax({
        url: base_url+ "api/site_info",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            let siteInfo = data.row, pkb = siteInfo.pkb;
            let headerLogo = `
                <img alt="Logo" src="` +siteInfo.headpublic_logo_url+ `" class="h-20px h-lg-30px theme-light-show" />
                <img alt="Logo-dark" src="` +siteInfo.headpublic_logo_dark_url+ `" class="h-20px h-lg-30px theme-dark-show" />
            `;
            $('#headerLogo').html(headerLogo);
            $('#contact_map').html(`<iframe class="w-100 rounded h-325px" src="https://maps.google.com/maps?q=` +pkb.address_coordinate+ `&z=15&output=embed" style="border:0;" allowfullscreen="false" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>`);
            $('#address_footer').html(`<i class="ki-duotone ki-geolocation fs-3tx text-primary"><span class="path1"></span><span class="path2"></span></i>
            <h1 class="text-gray-900 fw-bold my-5">
                Alamat
            </h1>
            <div class="text-gray-700 fs-3 fw-semibold hover-elevate-up">
                <a href="https://www.google.com/maps?saddr=My+Location&daddr=` +pkb.address_coordinate+ `" target="_blank" title="Rute alamat">` +pkb.address+ `</a>
            </div>`);
            $('#contact_center').html(`<i class="ki-duotone ki-message-text fs-3tx text-primary"><span class="path1"></span><span class="path2"></span></i>
            <h1 class="text-gray-900 fw-bold my-5">
                WhatsApp Center
            </h1>
            <div class="text-gray-700 fw-semibold fs-3 hover-elevate-up">
                <a href="https://api.whatsapp.com/send?phone=` +pkb.phone_number+ `" target="_blank" title="Kirim pesan WhatsApp Center">` +pkb.phone_number+ `</a>
            </div>`);
            $('#copyRight').html(siteInfo.copyright);
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
};
// Class Initialization
jQuery(document).ready(function() {
    _loadSystemInfo();
});
