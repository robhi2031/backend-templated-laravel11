/* *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** ***
/////////////////   Down Load Button Function   /////////////////
*** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** */
(function ($) {
    'use strict';
    $('#tm_download_btn').on('click', function () {
        let downloadSection = $('#tm_download_section');
        let cWidth = downloadSection.width();
        let cHeight = downloadSection.height();
        let topLeftMargin = 0;
        let pdfWidth = cWidth + topLeftMargin * 2;
        let pdfHeight = pdfWidth * 1.5 + topLeftMargin * 2;
        let canvasImageWidth = cWidth;
        let canvasImageHeight = cHeight;
        let totalPDFPages = Math.ceil(cHeight / pdfHeight) - 1;

        html2canvas(downloadSection[0], { allowTaint: true }).then(function (
            canvas
        ) {
            canvas.getContext('2d');
            let nameFile = $('title').text();
            let imgData = canvas.toDataURL('image/png', 1.0);
            let pdf = new jsPDF('p', 'pt', [pdfWidth, pdfHeight]);
            pdf.addImage(
                imgData,
                'PNG',
                topLeftMargin,
                topLeftMargin,
                canvasImageWidth,
                canvasImageHeight
            );
            for (let i = 1; i <= totalPDFPages; i++) {
                pdf.addPage(pdfWidth, pdfHeight);
                pdf.addImage(
                    imgData,
                    'PNG',
                    topLeftMargin,
                    -(pdfHeight * i) + topLeftMargin * 0,
                    canvasImageWidth,
                    canvasImageHeight
                );
            }
            pdf.save(nameFile+ '.pdf');
        });
    });
    // window.addEventListener(
    //     "contextmenu",
    //     function (t) {
    //         t.preventDefault();
    //     },
    //     !1
    // ),
    // (document.onkeydown = function (t) {
    //     return (
    //         123 != event.keyCode &&
    //         (!t.ctrlKey || !t.shiftKey || t.keyCode != "I".charCodeAt(0)) &&
    //         (!t.ctrlKey || !t.shiftKey || t.keyCode != "C".charCodeAt(0)) &&
    //         (!t.ctrlKey || !t.shiftKey || t.keyCode != "J".charCodeAt(0)) &&
    //         (!t.ctrlKey || t.keyCode != "U".charCodeAt(0)) &&
    //         void 0
    //     );
    // });
})(jQuery);
