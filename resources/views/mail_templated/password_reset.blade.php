<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ $data['subject'] }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
</head>
<!--end::Head-->
<!--begin::Body-->
<body>
    <div style="display: flex!important; flex: 1; flex-direction: column!important;">
        <!--begin::Email template-->
        <style>
            html, body {
                padding: 0;
                margin: 0;
                font-family: Inter, Helvetica, "sans-serif";
            }
            a:hover {
                color: #008D65;
            }
        </style>
        <div style="background-color:#D5D9E2; font-family:Arial,Helvetica,sans-serif; line-height: 1.5; min-height: 100%; font-weight: normal; font-size: 15px; color: #2F3044; margin:0; padding:0; width:100%;">
            <div style="background-color:#ffffff; padding: 45px 0 34px 0; border-radius: 24px; margin:40px auto; max-width: 600px;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="auto" style="border-collapse:collapse">
                    <tbody>
                        <tr>
                            <td align="center" valign="center" style="text-align:center; padding-bottom: 10px">
                                <!--begin:Email content-->
                                <div style="text-align:center;margin: 0 60px 0 60px;">
                                    <!--begin:Logo-->
                                    <div style="margin-bottom: 10px">
                                        <a href="{{ url('/') }}" rel="noopener" target="_blank">
                                            <img alt="Logo" src="{{ $data["siteInfo"]->url_frontendLogo }}" style="height: 35px" />
                                        </a>
                                    </div>
                                    <!--end:Logo-->
                                    <!--begin:Media-->
                                    <div style="margin-bottom: 15px">
                                        <img alt="Logo" src="{{ asset('/dist/img/refresh-lock.webp') }}" />
                                    </div>
                                    <!--end:Media-->
                                    <!--begin:Text-->
                                    <div
                                        style="font-size: 14px; font-weight: 500; margin-bottom: 27px; font-family:Arial,Helvetica,sans-serif;">
                                        <p style="margin-bottom:9px; color:#181C32; font-size: 22px; font-weight:700">Halo, {{ $data["userInfo"]['name'] }}</p>
                                        <p style="margin-bottom:2px; color:#7E8299">Sistem baru saja melakukan reset password akun anda pada Aplikasi <strong>{{ $data["siteInfo"]->short_name }}</strong>. Berikut ini informasi login beserta password baru yang bisa digunakan:</p>
                                    </div>
                                    <!--end:Text-->
                                </div>
                                <!--end:Email content-->
                            </td>
                        </tr>
                        <tr style="display: flex; justify-content: center; margin:0 60px 35px 60px">
                            <td align="start" valign="start" style="padding-bottom: 10px;">
                                <div style="background: #F9F9F9;border-radius: 12px;padding: 25px 30px;">
                                    <p style="color:#181C32;font-size: 18px;font-weight: 600;margin-top: 0;margin-bottom:0;text-align: center;text-decoration: underline;">Informasi Login</p>
                                    <!--begin::Item-->
                                    <div style="display: block; text-align: center;">
                                        <h4 tyle="color:#181C32; font-size: 14px; font-weight: 600;font-family:Arial,Helvetica,sans-serif" style="line-height: 1; margin-bottom: 0; margin-top: 10px;">Username : <span style="color: #e50000;">{{ $data["userInfo"]['username'] }}</span></h4>
                                        <h4 tyle="color:#181C32; font-size: 14px; font-weight: 600;font-family:Arial,Helvetica,sans-serif" style="line-height: 1; margin-bottom: 0; margin-top: 10px;">Password : <span style="color: #e50000;"><strong>{{ $data["userInfo"]['newPass'] }}</strong></span></h4>
                                    </div>
                                    <!--end::Item-->
                                    <small style="display: block; text-align: center; margin-top: 10px;">
                                        <strong>NOTED: Jangan lupa lakukan perubahan password setelah anda login, atau jika tidak silahkan catat/ ingat password baru ini untuk login kembali.</strong>
                                    </small>
                                </div>
                                <div style="text-align: center; margin-top: 10px;">
                                    <!--begin:Action-->
                                    <a href="{{ url('auth') }}" target="_blank" style="background-color:#50cd89; border-radius:6px;display:inline-block; padding:11px 19px; color: #FFFFFF; font-size: 14px; font-weight:500; font-family:Arial,Helvetica,sans-serif;">
                                        Login Now
                                    </a>
                                    <!--end:Action-->
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="center" style="font-size: 13px; text-align:center; padding: 0 10px 10px 10px; font-weight: 500; color: #A1A5B7; font-family:Arial,Helvetica,sans-serif">
                                <hr />
                                <small>
                                    Pesan ini dikirimkan kepada anda sebagai notifikasi reset password user. Tidak perlu membalas pesan ini karena dikirim melalui sistem dan mungkin response anda tidak akan dibalas, Terimakasih.
                                </small>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="center" style="font-size: 13px; padding:0 15px; text-align:center; font-weight: 500; color: #A1A5B7;font-family:Arial,Helvetica,sans-serif">
                                {!! $data["siteInfo"]->copyright !!}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Email template-->
    </div>
</body>
<!--end::Body-->
</html>
