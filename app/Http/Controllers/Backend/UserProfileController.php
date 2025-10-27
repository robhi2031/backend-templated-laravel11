<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\SiteCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Laravel\Facades\Image as Img;
use Illuminate\Support\Str;

class UserProfileController extends Controller
{
    use SiteCommon;
    /**
     * index
     *
     * @return void
     */
    public function index(Request $request, $username)
    {
        //Check username
        if(!User::whereUsername($username)->exists()) {
            return abort(404);
        }

        //Data WebInfo
        $getSiteInfo = $this->get_siteinfo();
        $getUserSession = Auth::user();
        $data = array(
            'title' => $getUserSession->name.' on User Profile',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/plugins/bootstrap-select/css/bootstrap-select.min.css',
            'dist/plugins/Magnific-Popup/magnific-popup.css'
        );
        //Data Source JS
        $data['js'] = array(
            'dist/plugins/bootstrap-select/js/bootstrap-select.min.js',
            'https://npmcdn.com/flatpickr@4.6.13/dist/l10n/id.js',
            'dist/js/jquery.mask.min.js',
            'dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js',
            config('app.env') === 'production' ? 'dist/js/app.backend.init.min.js' : 'dist/js/app.backend.init.js',
            config('app.env') === 'production' ? 'dist/scripts/backend/user_profile.init.min.js' : 'dist/scripts/backend/user_profile.init.js'
        );

        addToLog('Mengakses halaman ' .$data['title']. ' - Backend');
        return view('backend.user_profile', compact('data'));
    }
    /**
     * show
     *
     * @param  mixed $request
     * @return void
     */
    public function show(Request $request)
    {
        return jsonResponse(false, 'Page not found ...', 404);
    }
    /**
     * update
     *
     * @param  mixed $request
     * @return void
     */
    public function update(Request $request) {
        $userSesIdp = Auth::user()->id;
        if(isset($request->is_updatepass)) {
            //UPDATE PASS
            $form = [
                'pass_lama' => 'required|min:6|max:50',
                'pass_baru' => 'required|min:6|max:50',
                'repass_baru' => 'required|min:6|max:50'
            ];
            DB::beginTransaction();
            $request->validate($form);
            try {
                //Check Old Password
                $user = User::whereId($userSesIdp)->first();
                $hashedPassword = $user->password;
                if (!Hash::check($request->pass_lama, $hashedPassword)) {
                    addToLog('Password cannot be updated, The inputted old password is not found in the system');
                    return jsonResponse(false, 'Gagal memperbarui data! Password lama tidak valid, coba lagi dengan isian yang benar', 200, array('error_code' => 'invalid_passold'));
                }
                // Update the new Password
                User::whereId($userSesIdp)->update([
                    'password' => Hash::make($request->repass_baru),
                    'user_updated' => $userSesIdp
                ]);
                addToLog('User password has been successfully updated');
                DB::commit();
                return jsonResponse(true, 'Success', 200);
            } catch (Exception $exception) {
                DB::rollBack();
                return jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        } else {
            //UPDATE USER PROFILE
            $form = [
                'name' => 'required|max:100',
                'username' => 'required|max:100',
                'email' => 'required|max:225',
                'phone_number' => 'required|max:15',
                'avatar' => 'mimes:png,jpg,jpeg,webp|max:2048',
            ];
            DB::beginTransaction();
            $request->validate($form);
            try {
                if(User::whereEmail($request->email)->where('id', '!=', $userSesIdp)->exists()) {
                    addToLog('Data cannot be updated, the same email already exists in the system');
                    return jsonResponse(false, 'Gagal memperbarui data, email yang sama sudah ada pada sistem. Coba gunakan email yang lain', 200, array('error_code' => 'email_available'));
                }
                $data = array(
                    'name' => $request->name,
                    // 'username' => $request->username,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'user_updated' => $userSesIdp
                );
                //If Update Avatar User
                if(!empty($_FILES['avatar']['name'])) {
                    $avatarDestinationPath = public_path('/dist/img/users-img');
                    $getUser = User::whereId($userSesIdp)->first();
                    $getAvatarFile = $avatarDestinationPath.'/'.$getUser->thumb;
                    if(file_exists($getAvatarFile) && $getUser->thumb)
                        unlink($getAvatarFile);
                    $avatarFile = $request->file('avatar');
                    $avatarExtension = $avatarFile->getClientOriginalExtension();
                    //Cek and Create Destination Path
                    if(!is_dir($avatarDestinationPath)){ mkdir($avatarDestinationPath, 0755, TRUE); }
                    $avatarOriginName = $avatarFile->getClientOriginalName();
                    $avatarNewName = strtolower(Str::slug($request->username.bcrypt(pathinfo($avatarOriginName, PATHINFO_FILENAME)))) . time();
                    $avatarNewNameExt = $avatarNewName . '.webp';
                    $avatar = Img::read($avatarFile)->toWebp(60);
                    $avatar->save($avatarDestinationPath. '/' .$avatarNewNameExt);
                    $data['thumb'] = $avatarNewNameExt;
                }
                User::whereId($userSesIdp)->update($data);
                addToLog('The user profile data has been successfully updated');
                DB::commit();
                return jsonResponse(true, 'Profil User berhasil diperbarui', 200);
            } catch (Exception $exception) {
                DB::rollBack();
                return jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        }
    }
}
