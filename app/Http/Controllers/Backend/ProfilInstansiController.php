<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ProfilInstansi;
use App\Traits\SiteCommon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image as Img;

class ProfilInstansiController extends Controller
{
    use SiteCommon;

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('direct_permission:kelola-aplikasi-profil-instansi-read', only: ['index']),
            new Middleware('direct_permission:kelola-aplikasi-profil-instansi-update', only: ['update']),
        ];
    }

    public function index(Request $request)
    {
        $siteInfo = $this->get_siteinfo();
        $userSession = Auth::user();
        //Data WebInfo
        $data = array(
            'title' => 'Kelola Profil Instansi',
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
            'dist/js/jquery.mask.min.js',
            config('app.env') === 'production' ? 'dist/js/app.backend.init.min.js' : 'dist/js/app.backend.init.js',
            'https://maps.googleapis.com/maps/api/js?key=AIzaSyDECMLwt8UyWPgkKVqEqf5QGFcqOsP6VKs',
            config('app.env') === 'production' ? 'dist/scripts/backend/manage_profilinstansi.init.min.js' : 'dist/scripts/backend/manage_profilinstansi.init.js'
        );

        addToLog('Mengakses halaman ' . $data['title'] . ' - Backend');
        return view('backend.manage_profilinstansi', compact('data'));
    }
    /**
     * show
     *
     * @param  mixed $request
     * @return void
     */
    public function show(Request $request)
    {
        try {
            $output = array();
            $getRow = ProfilInstansi::whereId(1)->first();
            $whatsapp_blasting = 0;
            if($getRow != null){
                //Titik Lokasi Instansi
                $koordinatSplit = $getRow->office_address_coordinate ? explode(',', $getRow->office_address_coordinate) : NULL;
                $getRow->latitudeAddress = $getRow->office_address_coordinate ? $koordinatSplit[0] : NULL;
                $getRow->longitudeAddress = $getRow->office_address_coordinate ? $koordinatSplit[1] : NULL;
                //Logo Instansi
                $logo = $getRow->logo;
                if($logo==''){
                    $getRow->logo_url = NULL;
                } else {
                    if (!file_exists(public_path(). '/dist/img/profil-instansi-img/'.$logo)){
                        $getRow->logo_url = NULL;
                        $getRow->logo = NULL;
                    }else{
                        $getRow->logo_url = url('dist/img/profil-instansi-img/'.$logo);
                    }
                }
            } else {
                $getRow = null;
            }
            $output = [
                'instansi' => $getRow
            ];
            return jsonResponse(true, 'Success', 200, $output);
        } catch (Exception $exception) {
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
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
                'short_description' => 'required|max:255',
                'latitudeAddress' => 'required',
                'longitudeAddress' => 'required',
                'address' => 'required|max:225',
                'phone_number' => 'required|max:15',
                'email' => 'required|max:225'
            ];
        } else if ($form_type == 'logo') {
            $form = [
                'logo' => 'mimes:png,jpg,jpeg,webp|max:2048'
                // 'kop_surat' => 'mimes:png,jpg,jpeg,webp|max:2048'
            ];
        } else {
            return jsonResponse(false, "You've sent a bad request.", 400);
            addToLog("You've sent a bad request, form type not found");
        }
        DB::beginTransaction();
        $request->validate($form);
        try {
            $profilInstansi = ProfilInstansi::whereId(1)->first();
            if ($form_type == 'general') {
                $titik_coordinate = $request->latitudeAddress.','.$request->longitudeAddress;
                //array data
                $data = array(
                    'name' => $request->name,
                    'short_description' => $request->short_description,
                    'office_address_coordinate' => $titik_coordinate,
                    'office_address' => $request->address,
                    'phone_number' => $request->phone_number,
                    'email' => $request->email,
                    'user_updated' => $userSesIdp
                );
            } else if ($form_type == 'logo') {
                //array data
                $data = array(
                    'user_updated' => $userSesIdp
                );
                //Created or UPDATE FILE
                // if(!empty($_FILES['logo']['name']) || !empty($_FILES['kop_surat']['name'])) {
                if(!empty($_FILES['logo']['name'])) {
                    $destinationPath = public_path('/dist/img/profil-instansi-img');
                    //Cek and Create Destination Path
                    if(!is_dir($destinationPath)){ mkdir($destinationPath, 0755, TRUE); }
                    //Get & Cek File
                    $getFile = $profilInstansi;
                    if(!empty($_FILES['logo']['name'])){
                        if($getFile==true) {
                            $get_logo = $destinationPath.'/'.$getFile->logo;
                            if(file_exists($get_logo) && $getFile->logo)
                                unlink($get_logo);
                        }
                        $doUploadFile = $this->_doUploadFileProfilInstansi($request->file('logo'), $destinationPath, 'logo');
                        $data['logo'] = $doUploadFile['file_name'];
                    } /* if(!empty($_FILES['kop_surat']['name'])){
                        if($getFile==true) {
                            $get_kop_surat = $destinationPath.'/'.$getFile->kop_surat;
                            if(file_exists($get_kop_surat) && $getFile->kop_surat)
                                unlink($get_kop_surat);
                        }
                        $doUploadFile = $this->_doUploadFileProfilInstansi($request->file('kop_surat'), $destinationPath, 'kop_surat');
                        $data['kop_surat'] = $doUploadFile['file_name'];
                    } */
                }
            } else {
                return jsonResponse(false, "You've sent a bad request.", 400);
                addToLog("You've sent a bad request, form type not found");
            }
            //Insert or Update Profil Instansi
            if($profilInstansi == true) {
                ProfilInstansi::whereId(1)->update($data);
                addToLog('Profile Instansi has been successfully updated');
            } else {
                ProfilInstansi::insert($data);
                addToLog('Profile Instansi has been successfully added');
            }
            DB::commit();
            return jsonResponse(true, 'Profil Instansi berhasil diperbarui', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * _doUploadFileProfilInstansi
     *
     * @param  mixed $fileInput
     * @param  mixed $destinationPath
     * @param  mixed $nameInput
     * @return void
     */
    private function _doUploadFileProfilInstansi($fileInput, $destinationPath, $nameInput) {
        try {
            $fileExtension = $fileInput->getClientOriginalExtension();
            $fileOriginName = $fileInput->getClientOriginalName();
            $fileNewName = strtolower(Str::slug($nameInput.bcrypt(pathinfo($fileOriginName, PATHINFO_FILENAME)))) . time();
            // $fileNewNameExt = $fileNewName . '.' . $fileExtension;
            // $fileInput->move($destinationPath, $fileNewNameExt);
            /* $compressedImage = Image::make($fileInput)
                ->save($destinationPath . '/' . $fileNewNameExt, 10);
            return [
                'file_name' => $fileNewNameExt
            ]; */
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
