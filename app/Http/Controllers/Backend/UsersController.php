<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\MailNotifyPasswordReset;
use App\Models\User;
use App\Traits\Select2Common;
use App\Traits\SiteCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Intervention\Image\Laravel\Facades\Image as Img;

class UsersController extends Controller
{
    use SiteCommon;
    use Select2Common;
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('direct_permission:kelola-pengguna-users-read', only: ['index', 'show']),
            new Middleware('direct_permission:kelola-pengguna-users-create', only: ['store']),
            new Middleware('direct_permission:kelola-pengguna-users-update', only: ['update']),
            new Middleware('direct_permission:kelola-pengguna-users-delete', only: ['delete']),
        ];
    }
    /**
     * index
     *
     * @return void
     */
    public function index(Request $request)
    {
        $getSiteInfo = $this->get_siteinfo();
        $getUserSession = Auth::user();
        //Data WebInfo
        $data = array(
            'title' => 'Kelola Data User',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/plugins/custom/datatables/datatables.bundle.css',
            'dist/plugins/bootstrap-select/css/bootstrap-select.min.css',
            'dist/plugins/Magnific-Popup/magnific-popup.css'
        );
        //Data Source JS
        $data['js'] = array(
            'dist/plugins/custom/datatables/datatables.bundle.js',
            'dist/plugins/bootstrap-select/js/bootstrap-select.min.js',
            'dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js',
            'dist/js/jquery.mask.min.js',
            config('app.env') === 'production' ? 'dist/js/app.backend.init.min.js' : 'dist/js/app.backend.init.js',
            config('app.env') === 'production' ? 'dist/scripts/backend/manage_users.init.min.js' : 'dist/scripts/backend/manage_users.init.js'
        );

        addToLog('Mengakses halaman ' .$data['title']. ' - Backend');
        return view('backend.manage_users', compact('data'));
    }
    /**
     * show
     *
     * @param  mixed $request
     * @return void
     */
    public function show(Request $request)
    {
        if(isset($request->is_selectrow)) {
            if(isset($request->is_roles)){
                try {
                    $getRow = Role::get();
                    if(auth()->user()->getRoleNames()[0] != 'Super Admin') {
                        $getRow = Role::where('name', '!=', 'Super Admin')->get();
                    } if($getRow != null){
                        return jsonResponse(true, 'Success', 200, $getRow);
                    } else {
                        return jsonResponse(false, "Credentials not match", 401);
                    }
                } catch (Exception $exception) {
                    return jsonResponse(false, $exception->getMessage(), 401, [
                        "Trace" => $exception->getTrace()
                    ]);
                }
            }
        } else {
            if(isset($request->idp)){
                //USERS
                try {
                    $getRow = User::selectRaw("users_system.id, users_system.name, users_system.username,
                        users_system.email, users_system.phone_number, users_system.thumb, users_system.is_active,
                        users_system.is_login, users_system.ip_login, users_system.last_login, c.name AS role, b.role_id")
                        ->leftJoin('model_has_roles AS b', 'b.model_id', '=', 'users_system.id')
                        ->leftJoin('roles AS c', 'c.id', '=', 'b.role_id')
                        ->where('users_system.id', $request->idp)
                        ->first();
                    if($getRow != null){
                        //Thumb Site
                        $thumb = $getRow->thumb;
                        if($thumb==''){
                            $getRow->url_thumb = NULL;
                        } else {
                            if (!file_exists(public_path(). '/dist/img/users-img/'.$thumb)){
                                $getRow->url_thumb = NULL;
                                $getRow->thumb = NULL;
                            }else{
                                $getRow->url_thumb = url('dist/img/users-img/'.$thumb);
                            }
                        }
                        return jsonResponse(true, 'Success', 200, $getRow);
                    } else {
                        return jsonResponse(false, "Credentials not match", 401);
                    }
                } catch (Exception $exception) {
                    return jsonResponse(false, $exception->getMessage(), 401, [
                        "Trace" => $exception->getTrace()
                    ]);
                }
            } else {
                $query = User::selectRaw("users_system.id, users_system.name, users_system.username, users_system.email, users_system.phone_number, users_system.thumb, users_system.is_active,
                    users_system.is_login, users_system.ip_login, users_system.last_login, c.name AS role, b.role_id")
                    ->leftJoin('model_has_roles AS b', 'b.model_id', '=', 'users_system.id')
                    ->leftJoin('roles AS c', 'c.id', '=', 'b.role_id');
                if(auth()->user()->getRoleNames()[0] != 'Super Admin') {
                    $query = $query->where('c.name', '!=', 'Super Admin');
                }
                $data = $query->orderBy('c.id')->orderBy('users_system.id', 'desc')->get();
                $output = DataTables::of($data)->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1 ms-1" data-bs-toggle="tooltip" title="You don`t have permission to update!" disabled><i class="la la-edit fs-3"></i></button>';
                        $btnDelete = '';
                        $btnResetPass = '';
                        if(Auth::user()->can('kelola-pengguna-users-update')){
                            $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1 ms-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editUser('."'".$row->id."'".');"><i class="la la-edit fs-3"></i></button>';
                            if(Auth::user()->can('kelola-pengguna-users-delete')){
                                $btnDelete = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-danger mb-1 ms-1" data-bs-toggle="tooltip" title="Hapus data!" onclick="_deleteUser('."'".$row->id."'".');"><i class="las la-trash-alt fs-3"></i></button>';
                            }
                            $btnResetPass = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-warning mb-1 ms-1" data-bs-toggle="tooltip" title="Reset Password!" onclick="_resetUserPass('."'".$row->id."'".');"><i class="las la-unlock-alt fs-3"></i></button>';
                        }
                        return $btnEdit.$btnDelete.$btnResetPass;
                    })
                    ->editColumn('name', function ($row) {
                        $user_thumb = $row->thumb;
                        $getHurufAwal = $row->username[0];
                        $symbolThumb = strtoupper($getHurufAwal);
                        if($user_thumb == ''){
                            $url_userThumb = url('dist/img/default-user-img.jpg');
                            $userThumb = '<span class="symbol-label bg-secondary text-danger fw-bold fs-1">'.$symbolThumb.'</span>';
                        } else if (!file_exists(public_path(). '/dist/img/users-img/'.$user_thumb)){
                            $url_userThumb = url('dist/img/default-user-img.jpg');
                            $user_thumb = NULL;
                            $userThumb = '<span class="symbol-label bg-secondary text-danger fw-bold fs-1">'.$symbolThumb.'</span>';
                        }else{
                            $url_userThumb = url('dist/img/users-img/'.$user_thumb);
                            $userThumb = '<a class="image-popup" href="'.$url_userThumb.'" title="'.$user_thumb.'">
                                <div class="symbol-label">
                                    <img alt="'.$user_thumb.'" src="'.$url_userThumb.'" class="w-100" />
                                </div>
                            </a>';
                        }
                        $userCustom = '<div class="d-flex align-items-center">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-circle symbol-50px overflow-hidden">
                                '.$userThumb.'
                            </div>
                            <!--end::Avatar-->
                            <div class="ms-2">
                                <a href="javascript:void(0);" class="fw-bold text-gray-900 text-hover-primary mb-2">'.$row->name.'</a>
                                <div class="fw-bold text-muted">'.$row->username.'</div>
                            </div>
                        </div>';
                        return $userCustom;
                    })
                    ->editColumn('is_active', function ($row) {
                        $active = '';
                        $title = 'Status Tidak Aktif, Aktifkan?';
                        if($row->is_active == 'Y'){
                            $active = 'checked';
                            $title = 'Status Aktif, Nonaktifkan?';
                        }
                        $activeCustom = '<div class="form-check form-switch form-check-custom form-check-solid form-check-success is-public" data-bs-toggle="tooltip" title="' .$title. '">
                            <input class="form-check-input cursor-pointer" type="checkbox" row-id="' .$row->id. '" ' .$active. ' />
                        </div>';
                        return $activeCustom;
                    })
                    ->editColumn('last_login', function ($row) {
                        $ipLogin = $row->ip_login;
                        $lastLogin = $row->last_login;
                        if($ipLogin == '' || $ipLogin == null) { $ipLogin = '-';}
                        if($lastLogin == '' || $lastLogin == null) { $lastLogin = '-'; } else { $lastLogin = time_ago($lastLogin); }
                        $last_login = $ipLogin.' <br/><div class="fw-bold text-muted">'.$lastLogin.'</div>';
                        return $last_login;
                    })
                    ->rawColumns(['action', 'name', 'is_active', 'last_login'])
                    ->make(true);

                return $output;
            }
        }
    }
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request) {
        $userSesIdp = Auth::user()->id;
        $form = [
            'role' => 'required',
            'username' => 'required|max:50',
            'email' => 'required|max:225',
            'phone_number' => 'required|max:15',
            'pass_user' => 'required|min:6',
            'repass_user' => 'required|min:6',
            'avatar' => 'mimes:png,jpg,jpeg,webp|max:2048',
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            if(User::whereUsername($request->username)->exists()) {
                addToLog('Data cannot be saved, the same username already exists in the system');
                return jsonResponse(false, 'Gagal menambahkan data, username yang sama sudah ada pada sistem. Coba gunakan username yang lain', 200, array('error_code' => 'username_available'));
            } if(User::whereEmail($request->email)->exists()) {
                addToLog('Data cannot be saved, the same email already exists in the system');
                return jsonResponse(false, 'Gagal menambahkan data, email yang sama sudah ada pada sistem. Coba gunakan email yang lain', 200, array('error_code' => 'email_available'));
            }
            //array data
            $data = array(
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => bcrypt($request->repass_user),
                'user_add' => $userSesIdp
            );
            //File Avatar Upload
            $avatarDestinationPath = public_path('/dist/img/users-img');
            //If Input Avatar User
            if(!empty($_FILES['avatar']['name'])) {
                $data['thumb'] = $this->uploadAvatarUser($avatarDestinationPath, $request->file('avatar'), $request->username);
            }
            $insertUser = User::insertGetId($data);
            addToLog('User has been successfully added');
            //Asign Role & Permissions to User
            $this->assignRoleToUser($insertUser, '', $request->role);
            DB::commit();
            return jsonResponse(true, 'Data user berhasil ditambahkan', 200);
        } catch (Exception $exception) {
            DB::rollBack();
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
    public function update(Request $request) {
        $userSesIdp = Auth::user()->id;
        if(isset($request->update_status)) {
            //UPDATE STATUS
            $idp = $request->idp;
            $value = $request->value;
            DB::beginTransaction();
            try {
                $data = array(
                    'is_active' => $value,
                    'user_updated' => $userSesIdp
                );
                User::whereId($idp)->update($data);
                if($value=='N') {
                    addToLog('User status has been successfully updated to Inactive');
                    $textMsg = 'Status user berhasil diubah menjadi <strong>Nonaktif</strong>';
                } else {
                    addToLog('User status has been successfully updated to Active');
                    $textMsg = 'Status user berhasil diubah menjadi <strong>Aktif</strong>';
                }
                DB::commit();
                return jsonResponse(true, $textMsg, 200);
            } catch (Exception $exception) {
                DB::rollBack();
                return jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        } else if(isset($request->reset_pass)) {
            //RESET PASSWORD
            $idp = $request->idp;
            $textMsg = "";
            DB::beginTransaction();
            try {
                $getUser = User::whereId($idp)->first();
                $randPass = Str::random(6);
                $newPass = $randPass;
                $data = array(
                    'password' => bcrypt($newPass),
                    'user_updated' => $userSesIdp
                );

                $dataMail = [
                    "subject" => "Informasi Reset Password User",
                    "siteInfo" => $this->get_siteinfo(),
                    "userInfo" => [
                        "name" => $getUser->name,
                        "email" => $getUser->email,
                        "username" => $getUser->username,
                        "newPass" => $newPass,
                    ],
                ];
                //Send Mail to User custom Password
                if (Mail::to($getUser->email)->send(new MailNotifyPasswordReset($dataMail))) {
                    User::whereId($idp)->update($data);
                    addToLog('User has been successfully reset password');
                    addToLog('New password successfully sent to user via email message');
                    $textMsg = "Reset password user berhasil dilakukan, Password baru telah dikirimkan kepada user <strong>" .$getUser->name. "</strong> melalui pesan email";
                } else {
                    addToLog('New password failed to be sent to the user via email message');
                    $textMsg = "Reset password user berhasil dilakukan, Namun password baru gagal dikirimkan kepada user <strong>" .$getUser->name. "</strong> melalui pesan email, Anda dapat memberitahukan password baru kepada user tersebut dan memintanya untuk segera melakukan perubahan password setelah berhasil login. <strong>Password Baru: " .$newPass. "</strong>";
                }
                DB::commit();
                return jsonResponse(true, $textMsg, 200);
            } catch (Exception $exception) {
                DB::rollBack();
                return jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        } else {
            // USER
            $form = [
                'role' => 'required',
                'username' => 'required|max:50',
                'email' => 'required|max:225',
                'phone_number' => 'required|max:15',
                'avatar' => 'mimes:png,jpg,jpeg,webp|max:2048',
            ];
            DB::beginTransaction();
            $request->validate($form);
            try {if(User::whereUsername($request->username)->where('id', '!=', $request->id)->exists()) {
                    addToLog('Data cannot be saved, the same username already exists in the system');
                    return jsonResponse(false, 'Gagal menambahkan data, username yang sama sudah ada pada sistem. Coba gunakan username yang lain', 200, array('error_code' => 'username_available'));
                } if(User::whereEmail($request->email)->where('id', '!=', $request->id)->exists()) {
                    addToLog('Data cannot be saved, the same email already exists in the system');
                    return jsonResponse(false, 'Gagal menambahkan data, email yang sama sudah ada pada sistem. Coba gunakan email yang lain', 200, array('error_code' => 'email_available'));
                }
                //array data
                $data = array(
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'is_active' => isset($request->is_active) ? 'Y' : 'N',
                    'user_updated' => $userSesIdp
                );
                //File Avatar Upload
                $getUser = User::select()->whereId($request->id)->first();
                $avatarDestinationPath = public_path('/dist/img/users-img');
                if(!empty($_FILES['avatar']['name'])) {
                    $getAvatarFile = $avatarDestinationPath.'/'.$getUser->thumb;
                    if(file_exists($getAvatarFile) && $getUser->thumb)
                        unlink($getAvatarFile);
                    $data['thumb'] = $this->uploadAvatarUser($avatarDestinationPath, $request->file('avatar'), $request->username);
                }
                User::whereId($request->id)->update($data);
                addToLog('User has been successfully updated');
                if($request->role != $request->oldRole_id) {
                    //Revoke & Asign Role & Permissions to User
                    $this->assignRoleToUser($request->id, $request->oldRole_id, $request->role);
                }
                DB::commit();
                return jsonResponse(true, 'Data User berhasil diperbarui', 200);
            } catch (Exception $exception) {
                DB::rollBack();
                return jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        }
    }
    /**
     * uploadAvatarUser
     *
     * @param  mixed $path
     * @param  mixed $file
     * @param  mixed $username
     * @return void
     */
    private function uploadAvatarUser ($path, $file, $username) {
        $avatarDestinationPath = $path;
        $avatarExtension = $file->getClientOriginalExtension();
        if(!is_dir($avatarDestinationPath)){ mkdir($avatarDestinationPath, 0755, TRUE); }
        $avatarOriginName = $file->getClientOriginalName();
        $avatarNewName = strtolower(Str::slug($username.bcrypt(pathinfo($avatarOriginName, PATHINFO_FILENAME)))) . time();
        $avatarNewNameExt = $avatarNewName . '.webp';
        $avatar = Img::read($file)->toWebp(60);
        $avatar->save($avatarDestinationPath. '/' .$avatarNewNameExt);
        return $avatarNewNameExt;
    }
    /**
     * assignRoleToUser
     *
     * @param  mixed $idpUser
     * @param  mixed $idpRole
     * @return void
     */
    private function assignRoleToUser($idpUser, $oldIdpRole, $idpRole) {
        DB::beginTransaction();
        try {
            $getUser = User::whereId($idpUser)->first();
            $getPermissions = Permission::select('permissions.*')
                ->leftJoin('role_has_permissions AS b', 'b.permission_id', '=', 'permissions.id')
                ->where('b.role_id', $idpRole)
                ->get();
            $currentRole = Role::whereId($idpRole)->first()->name;
            if($oldIdpRole != '' || $oldIdpRole != null) {
                if($oldIdpRole != $idpRole) {
                    $oldRole = Role::whereId($oldIdpRole)->first()->name;
                    $getOldPermissions = Permission::select('permissions.*')
                        ->leftJoin('role_has_permissions AS b', 'b.permission_id', '=', 'permissions.id')
                        ->where('b.role_id', $oldIdpRole)
                        ->get();
                    if(count($getOldPermissions) > 0) {
                        foreach ($getOldPermissions as $row) {
                            $getUser->revokePermissionTo($row->name);
                        }
                    }
                    $getUser->removeRole($oldRole);
                }
            } if(count($getPermissions) > 0) {
                foreach ($getPermissions as $row) {
                    $getUser->givePermissionTo($row->name);
                }
            }
            $getUser->assignRole($currentRole);
            DB::commit();
        } catch (Exception $exception) {
            // dd($exception);
            DB::rollBack();
        }
    }
    /**
     * delete
     *
     * @param  mixed $request
     * @return void
     */
    public function delete(Request $request) {
        $idp = $request->idp;
        DB::beginTransaction();
        try {
            $getUser = User::selectRaw('users_system.id, b.role_id, c.name AS role_name, users_system.thumb')
                ->leftJoin('model_has_roles AS b', 'b.model_id', '=', 'users_system.id')
                ->leftJoin('roles AS c', 'c.id', '=', 'b.role_id')
                ->where('users_system.id', $idp)
                ->first();
            // Remove Role & Permissions
            $getPermissions = Permission::select('permissions.*')
                ->leftJoin('role_has_permissions AS b', 'b.permission_id', '=', 'permissions.id')
                ->where('b.role_id', $getUser->role_id)
                ->get();
            if(count($getPermissions) > 0) {
                foreach ($getPermissions as $row) {
                    $getUser->revokePermissionTo($row->name);
                }
            }
            $getUser->removeRole($getUser->role_name);
            //Remove Thumb
            $destinationPath = public_path('/dist/img/users-img');
            $getThumb = $destinationPath.'/'.$getUser->thumb;
            if(file_exists($getThumb) && $getUser->thumb)
                unlink($getThumb);
            //Delete User
            User::whereId($idp)->delete();
            addToLog('User data has been successfully deleted');
            $textMsg = 'Data user berhasil dihapus';

            DB::commit();
            return jsonResponse(true, $textMsg, 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
}
