<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SiteInfo;
use App\Traits\SiteCommon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image as Img;

class SiteInfoController extends Controller implements HasMiddleware
{
    use SiteCommon;

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('direct_permission:kelola-aplikasi-informasi-web-read', only: ['index']),
            new Middleware('direct_permission:kelola-aplikasi-informasi-web-update', only: ['update']),
        ];
    }

    public function index(Request $request)
    {
        $siteInfo = $this->get_siteinfo();
        $userSession = Auth::user();
        //Data WebInfo
        $data = array(
            'title' => 'Kelola Informasi Web',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $siteInfo->name,
            'user_session' => $userSession
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/plugins/summernote/summernote-lite.min.css',
            'dist/plugins/dropify-master/css/dropify.min.css',
            'dist/plugins/Magnific-Popup/magnific-popup.css'
        );
        //Data Source JS
        $data['js'] = array(
            'dist/plugins/summernote/summernote-lite.min.js',
            'dist/plugins/summernote/lang/summernote-id-ID.min.js',
            'dist/plugins/dropify-master/js/dropify.min.js',
            'dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js',
            config('app.env') === 'production' ? 'dist/js/app.backend.init.min.js' : 'dist/js/app.backend.init.js',
            config('app.env') === 'production' ? 'dist/scripts/backend/manage_siteinfo.init.min.js' : 'dist/scripts/backend/manage_siteinfo.init.js'
        );

        addToLog('Mengakses halaman ' . $data['title'] . ' - Backend');
        return view('backend.manage_siteinfo', compact('data'));
    }
    /**
     * update
     *
     * @param  mixed $request
     * @return void
     */
    public function update(Request $request)
    {
        $userSesIdp = Auth::user()->id;
        $form_type = $request->form_type;
        if ($form_type == 'general') {
            $form = [
                'name' => 'required|max:255',
                'short_name' => 'required|max:60',
                'description' => 'required|max:160',
                'keyword' => 'required',
                'copyright' => 'required'
            ];
        } else if ($form_type == 'logo_and_others') {
            $form = [
                'login_logo' => 'mimes:png,jpg,jpeg,webp|max:2048',
                'login_bg' => 'mimes:png,jpg,jpeg,webp|max:2048',
                'headbackend_logo' => 'mimes:png,jpg,jpeg,webp|max:2048',
                'headbackend_logo_dark' => 'mimes:png,jpg,jpeg,webp|max:2048',
                'headbackend_icon' => 'mimes:png,jpg,jpeg,webp|max:2048',
                'headbackend_icon_dark' => 'mimes:png,jpg,jpeg,webp|max:2048'
            ];
        } else {
            return jsonResponse(false, "You've sent a bad request.", 400);
            addToLog("You've sent a bad request, form type not found");
        }
        DB::beginTransaction();
        $request->validate($form);
        try {
            if ($form_type == 'general') {
                //keyword to implode
                if (!empty($request->keyword)) {
                    $keyword = implode(", ", $request->keyword);
                }
                //array data
                $data = array(
                    'name' => $request->name,
                    'short_name' => $request->short_name,
                    'description' => $request->description,
                    'keyword' => $keyword,
                    'copyright' => urldecode(urldecode($request->copyright))
                );
            } else {
                //UPDATE FILE
                if (
                    !empty($_FILES['login_logo']['name']) ||
                    !empty($_FILES['login_bg']['name']) ||
                    !empty($_FILES['headbackend_logo']['name']) ||
                    !empty($_FILES['headbackend_logo_dark']['name']) ||
                    !empty($_FILES['headbackend_icon']['name']) ||
                    !empty($_FILES['headbackend_icon_dark']['name'])
                ) {
                    $destinationPath = public_path('/dist/img/site-img');
                    //Cek and Create Destination Path
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0755, TRUE);
                    }
                    //Get & Cek File
                    $getFile = SiteInfo::whereId(1)->first();
                    //Login Logo
                    if (!empty($_FILES['login_logo']['name'])) {
                        if ($getFile == true) {
                            $getFileLoginLogo = $destinationPath . '/' . $getFile->login_logo;
                            if (file_exists($getFileLoginLogo) && $getFile->login_logo)
                                unlink($getFileLoginLogo);
                        }
                        $doUploadFile = $this->_doUploadFileSiteInfo($request->file('login_logo'), $destinationPath, 'login_logo');
                        $data['login_logo'] = $doUploadFile['file_name'];
                    }
                    //Login Background
                    if (!empty($_FILES['login_bg']['name'])) {
                        if ($getFile == true) {
                            $getFileLoginBg = $destinationPath . '/' . $getFile->login_bg;
                            if (file_exists($getFileLoginBg) && $getFile->login_bg)
                                unlink($getFileLoginBg);
                        }
                        $doUploadFile = $this->_doUploadFileSiteInfo($request->file('login_bg'), $destinationPath, 'login_bg');
                        $data['login_bg'] = $doUploadFile['file_name'];
                    }
                    //Backend Logo
                    if (!empty($_FILES['headbackend_logo']['name'])) {
                        if ($getFile == true) {
                            $get_headbackend_logo = $destinationPath . '/' . $getFile->headbackend_logo;
                            if (file_exists($get_headbackend_logo) && $getFile->headbackend_logo)
                                unlink($get_headbackend_logo);
                        }
                        $doUploadFile = $this->_doUploadFileSiteInfo($request->file('headbackend_logo'), $destinationPath, 'headbackend_logo');
                        $data['headbackend_logo'] = $doUploadFile['file_name'];
                    }
                    if (!empty($_FILES['headbackend_logo_dark']['name'])) {
                        if ($getFile == true) {
                            $get_headbackend_logo_dark = $destinationPath . '/' . $getFile->headbackend_logo_dark;
                            if (file_exists($get_headbackend_logo_dark) && $getFile->headbackend_logo_dark)
                                unlink($get_headbackend_logo_dark);
                        }
                        $doUploadFile = $this->_doUploadFileSiteInfo($request->file('headbackend_logo_dark'), $destinationPath, 'headbackend_logo_dark');
                        $data['headbackend_logo_dark'] = $doUploadFile['file_name'];
                    }
                    //Backend Icon Logo
                    if (!empty($_FILES['headbackend_icon']['name'])) {
                        if ($getFile == true) {
                            $get_headbackend_icon = $destinationPath . '/' . $getFile->headbackend_icon;
                            if (file_exists($get_headbackend_icon) && $getFile->headbackend_icon)
                                unlink($get_headbackend_icon);
                        }
                        $doUploadFile = $this->_doUploadFileSiteInfo($request->file('headbackend_icon'), $destinationPath, 'headbackend_icon');
                        $data['headbackend_icon'] = $doUploadFile['file_name'];
                    }
                    if (!empty($_FILES['headbackend_icon_dark']['name'])) {
                        if ($getFile == true) {
                            $get_headbackend_icon_dark = $destinationPath . '/' . $getFile->headbackend_icon_dark;
                            if (file_exists($get_headbackend_icon_dark) && $getFile->headbackend_icon_dark)
                                unlink($get_headbackend_icon_dark);
                        }
                        $doUploadFile = $this->_doUploadFileSiteInfo($request->file('headbackend_icon_dark'), $destinationPath, 'headbackend_icon_dark');
                        $data['headbackend_icon_dark'] = $doUploadFile['file_name'];
                    }
                }
            }
            $data['user_updated'] = $userSesIdp;
            SiteInfo::whereId(1)->update($data);
            addToLog('The site info has been successfully updated');
            DB::commit();
            return jsonResponse(true, 'Informasi situs web berhasil diperbarui', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return jsonResponse(false, $exception->getMessage(), 400, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * _doUploadFileSiteInfo
     *
     * @param  mixed $fileInput
     * @param  mixed $destinationPath
     * @param  mixed $nameInput
     * @return void
     */
    private function _doUploadFileSiteInfo($fileInput, $destinationPath, $nameInput)
    {
        try {
            $fileOriginName = $fileInput->getClientOriginalName();
            $fileNewName = strtolower(Str::slug($nameInput . bcrypt(pathinfo($fileOriginName, PATHINFO_FILENAME)))) . time();
            $fileNewNameExt = $fileNewName . '.webp';
            $image = Img::read($fileInput)->toWebp(60);
            $image->save($destinationPath . '/' . $fileNewNameExt);
            return [
                'file_name' => $fileNewNameExt
            ];
        } catch (Exception $exception) {
            return [
                "Message" => $exception->getMessage(),
                "Trace" => $exception->getTrace()
            ];
        }
    }
}
