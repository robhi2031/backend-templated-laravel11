<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Traits\Select2Common;
use App\Traits\SiteCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RolesController extends Controller
{
    use SiteCommon;
    use Select2Common;

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('direct_permission:kelola-pengguna-roles-read', only: ['index', 'show']),
            new Middleware('direct_permission:kelola-pengguna-roles-create', only: ['store']),
            new Middleware('direct_permission:kelola-pengguna-roles-update', only: ['update']),
            new Middleware('direct_permission:kelola-pengguna-roles-delete', only: ['delete']),
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
            'title' => 'Kelola User Roles',
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
            config('app.env') === 'production' ? 'dist/js/app.backend.init.min.js' : 'dist/js/app.backend.init.js',
            config('app.env') === 'production' ? 'dist/scripts/backend/manage_roles.init.min.js' : 'dist/scripts/backend/manage_roles.init.js'
        );

        addToLog('Mengakses halaman ' .$data['title']. ' - Backend');
        return view('backend.manage_roles', compact('data'));
    }

    /**
     * show
     *
     * @param  mixed $request
     * @return void
     */
    public function show(Request $request)
    {
        if (isset($request->select2)) {
            if(isset($request->is_permissionsbyrole)) {
                //SELECT2
                $params = [
                    'search' => $request->search,
                    'page' => $request->page,
                    'role' => $request->role_id
                ];
                try {
                    $output = $this->select2_permission($params);
                    return jsonResponse(true, 'Success', 200, $output);
                } catch (Exception $exception) {
                    return jsonResponse(false, $exception->getMessage(), 401, [
                        "Trace" => $exception->getTrace()
                    ]);
                }
            }
        } else if(isset($request->is_permissions)) {
            //PERMISSIONS
            if(isset($request->menu_id)) {
                try {
                    $result = array();
                    $menu_id = $request->menu_id;
                    $create = Permission::where('fid_menu', $menu_id)->whereRaw('RIGHT(name, 6)='."'create'".'')->first();
                    $create_check = 'unchecked';
                    if($create == true) {
                        $create_check = 'checked';
                    }
                    $read = Permission::where('fid_menu', $menu_id)->whereRaw('RIGHT(name, 4)='."'read'".'')->first();
                    $read_check = 'unchecked';
                    if($read == true) {
                        $read_check = 'checked';
                    }
                    $update = Permission::where('fid_menu', $menu_id)->whereRaw('RIGHT(name, 6)='."'update'".'')->first();
                    $update_check = 'unchecked';
                    if($update == true) {
                        $update_check = 'checked';
                    }
                    $delete = Permission::where('fid_menu', $menu_id)->whereRaw('RIGHT(name, 6)='."'delete'".'')->first();
                    $delete_check = 'unchecked';
                    if($delete == true) {
                        $delete_check = 'checked';
                    }
                    $result = [
                        'create' => $create_check,
                        'read' => $read_check,
                        'update' => $update_check,
                        'delete' => $delete_check,
                    ];
                    return jsonResponse(true, 'Success', 200, $result);
                } catch (Exception $exception) {
                    return jsonResponse(false, $exception->getMessage(), 401, [
                        "Trace" => $exception->getTrace()
                    ]);
                }
            } else {
                $idp = $request->idp;
                $data = DB::table('permission_has_menus AS a')
                    ->selectRaw("a.id, a.name, a.parent_id")
                    ->leftJoin('permissions AS b', 'b.fid_menu', '=', 'a.id')
                    // ->leftJoin('role_has_permissions AS c', 'c.permission_id', '=', 'b.id')
                    // ->leftJoin('roles AS d', 'd.id', '=', 'c.role_id')
                    // ->where('c.role_id', $idp)
                    // ->where('a.has_child', 'N')
                    ->groupBy('a.id')
                    ->orderBy('a.order_line', 'ASC')
                    ->get();

                $output = Datatables::of($data)->addIndexColumn()
                    ->editColumn('name', function ($row) {
                        $nameCustom = $row->name;
                        if($row->parent_id!=null || $row->parent_id!='') {
                            $nameCustom = '- ' .$row->name;
                        }
                        return $nameCustom;
                    })
                    ->addColumn('read', function($row) use ($idp) {
                        $subRow = DB::table('permissions AS a')
                        ->leftJoin('role_has_permissions AS b', 'b.permission_id', '=', 'a.id')
                        ->leftJoin('roles AS c', 'c.id', '=', 'b.role_id')
                        ->where('b.role_id', $idp)
                        ->where('a.fid_menu', $row->id)
                        ->whereRaw('RIGHT(a.name, 4)='."'read'".'')
                        ->first();
                        $checked = '';
                        $cursor = 'cursor-pointer';
                        $disabled = '';
                        if($subRow==true){
                            $checked = 'checked';
                        } else {
                            $checkPermission = Permission::where('fid_menu', $row->id)->whereRaw('RIGHT(name, 4)='."'read'".'')->first();
                            if($checkPermission == false) {
                                $cursor = 'cursor-not-allowed';
                                $disabled = 'disabled';
                            }
                        }
                        $check = '<div class="form-check form-check-custom form-check-solid form-check-success justify-content-center">
                            <input class="form-check-input pe-auto ' .$cursor. '" type="checkbox" data-menuId="' .$row->id. '" data-roleId="' .$idp. '" data-type="read" value="true" ' .$checked. ' ' .$disabled. ' />
                        </div>';
                        return $check;
                    })
                    ->addColumn('create', function($row) use ($idp) {
                        $subRow = DB::table('permissions AS a')
                        ->leftJoin('role_has_permissions AS b', 'b.permission_id', '=', 'a.id')
                        ->leftJoin('roles AS c', 'c.id', '=', 'b.role_id')
                        ->where('b.role_id', $idp)
                        ->where('a.fid_menu', $row->id)
                        ->whereRaw('RIGHT(a.name, 6)='."'create'".'')
                        ->first();
                        $checked = '';
                        $cursor = 'cursor-pointer';
                        $disabled = '';
                        if($subRow==true){
                            $checked = 'checked';
                        } else {
                            $checkPermission = Permission::where('fid_menu', $row->id)->whereRaw('RIGHT(name, 6)='."'create'".'')->first();
                            if($checkPermission == false) {
                                $cursor = 'cursor-not-allowed';
                                $disabled = 'disabled';
                            }
                        }
                        $check = '<div class="form-check form-check-custom form-check-solid form-check-success justify-content-center">
                            <input class="form-check-input pe-auto ' .$cursor. '" type="checkbox" data-menuId="' .$row->id. '" data-roleId="' .$idp. '" data-type="create" value="true" ' .$checked. ' ' .$disabled. ' />
                        </div>';
                        return $check;
                    })
                    ->addColumn('update', function($row) use ($idp) {
                        $subRow = DB::table('permissions AS a')
                        ->leftJoin('role_has_permissions AS b', 'b.permission_id', '=', 'a.id')
                        ->leftJoin('roles AS c', 'c.id', '=', 'b.role_id')
                        ->where('b.role_id', $idp)
                        ->where('a.fid_menu', $row->id)
                        ->whereRaw('RIGHT(a.name, 6)='."'update'".'')
                        ->first();
                        $checked = '';
                        $cursor = 'cursor-pointer';
                        $disabled = '';
                        if($subRow==true){
                            $checked = 'checked';
                        } else {
                            $checkPermission = Permission::where('fid_menu', $row->id)->whereRaw('RIGHT(name, 6)='."'update'".'')->first();
                            if($checkPermission == false) {
                                $cursor = 'cursor-not-allowed';
                                $disabled = 'disabled';
                            }
                        }
                        $check = '<div class="form-check form-check-custom form-check-solid form-check-success justify-content-center">
                            <input class="form-check-input pe-auto ' .$cursor. '" type="checkbox" data-menuId="' .$row->id. '" data-roleId="' .$idp. '" data-type="update" value="true" ' .$checked. ' ' .$disabled. ' />
                        </div>';
                        return $check;
                    })
                    ->addColumn('delete', function($row) use ($idp) {
                        $subRow = DB::table('permissions AS a')
                        ->leftJoin('role_has_permissions AS b', 'b.permission_id', '=', 'a.id')
                        ->leftJoin('roles AS c', 'c.id', '=', 'b.role_id')
                        ->where('b.role_id', $idp)
                        ->where('a.fid_menu', $row->id)
                        ->whereRaw('RIGHT(a.name, 6)='."'delete'".'')
                        ->first();
                        $checked = '';
                        $cursor = 'cursor-pointer';
                        $disabled = '';
                        if($subRow==true){
                            $checked = 'checked';
                        } else {
                            $checkPermission = Permission::where('fid_menu', $row->id)->whereRaw('RIGHT(name, 6)='."'delete'".'')->first();
                            if($checkPermission == false) {
                                $cursor = 'cursor-not-allowed';
                                $disabled = 'disabled';
                            }
                        }
                        $check = '<div class="form-check form-check-custom form-check-solid form-check-success justify-content-center">
                            <input class="form-check-input pe-auto ' .$cursor. '" type="checkbox" data-menuId="' .$row->id. '" data-roleId="' .$idp. '" data-type="delete" value="true" ' .$checked. ' ' .$disabled. ' />
                        </div>';
                        return $check;
                    })
                    ->rawColumns(['read', 'create', 'update', 'delete'])
                    ->make(true);

                return $output;
            }
        } else {
            // ROLES
            if(isset($request->idp)){
                try {
                    $getRow = Role::select('id', 'name')->where('id', $request->idp)->first();
                    if($getRow != null){
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
                $data = Role::orderByDesc('id')->get();
                $output = DataTables::of($data)->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editRole('."'".$row->id."'".');"><i class="la la-edit fs-3"></i></button>';
                        $btnDelete = '';
                        if(Auth::user()->can('kelola-pengguna-roles-delete')){
                            $btnDelete = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-danger mb-1 ms-1" data-bs-toggle="tooltip" title="Hapus data!" onclick="_deleteRole('."'".$row->id."'".');"><i class="las la-trash-alt fs-3"></i></button>';
                        }
                        $btnPermissions = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-warning mb-1 ms-1" data-bs-toggle="tooltip" title="Setting Permissions?" onclick="_settingPermissions('."'".$row->id."'".', '."'".$row->name."'".');"><i class="las la-tasks fs-3"></i></button>';
                        return $btnEdit.$btnDelete.$btnPermissions;
                    })
                    ->addColumn('permission_count', function($row){
                        $counterPermissions = DB::table('role_has_permissions')->distinct()->where('role_id', $row->id)->count('permission_id');
                        return '<span class="badge py-3 px-4 fs-7 badge-light-primary">' .$counterPermissions. '</span>';
                    })
                    ->addColumn('user_count', function($row){
                        $counterUsers = DB::table('model_has_roles')->distinct()->where('role_id', $row->id)->count('model_id');
                        return '<span class="badge py-3 px-4 fs-7 badge-light-primary">' .$counterUsers. '</span>';
                    })
                    ->rawColumns(['action', 'permission_count', 'user_count'])
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
        if(isset($request->is_permissions)) {
            $form = [
                'cbo_permission' => 'required',
            ];
            $idpRole = $request->idpRole;
            $IdpPermission = $request->cbo_permission;
            $create = isset($request->create) ? (int)$request->create : 0;
            $read = isset($request->read) ? (int)$request->read : 0;
            $update = isset($request->update) ? (int)$request->update : 0;
            $delete = isset($request->delete) ? (int)$request->delete : 0;
            DB::beginTransaction();
            $request->validate($form);
            try {
                //Create
                $this->_storePermissionToRole($idpRole, $IdpPermission, $create, 'create');
                //Read
                $this->_storePermissionToRole($idpRole, $IdpPermission, $read, 'read');
                //Update
                $this->_storePermissionToRole($idpRole, $IdpPermission, $update, 'update');
                //Delete
                $this->_storePermissionToRole($idpRole, $IdpPermission, $delete, 'delete');

                addToLog('Assign permission to Role has been successfully Add');
                $textMsg = 'Menu/ Permission baru berhasil ditambahkan pada role';
                DB::commit();
                return jsonResponse(true, $textMsg, 200);
            } catch (Exception $exception) {
                DB::rollBack();
                return jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        } else {
            $form = [
                'name' => 'required|max:50',
            ];
            DB::beginTransaction();
            $request->validate($form);
            try {
                if(Role::whereName($request->name)->exists()) {
                    addToLog('Data cannot be saved, the same Role name already exists in the system');
                    return jsonResponse(false, 'Gagal menambahkan data, Role yang sama sudah ada pada sistem. Coba gunakan nama role yang berbeda', 200, array('error_code' => 'name_available'));
                }
                //array data
                $data = array(
                    'name' => $request->name,
                    'guard_name' => 'web',
                    'user_add' => $userSesIdp
                );
                Role::insert($data);
                addToLog('Role has been successfully added');
                DB::commit();
                return jsonResponse(true, 'Role berhasil ditambahkan', 200);
            } catch (Exception $exception) {
                DB::rollBack();
                return jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
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
        if(isset($request->is_permissions)) {
            $idpMenu = $request->idpMenu;
            $idpRole = $request->idpRole;
            $value = $request->value;
            $type = $request->type;
            $lengthType = 6;
            if($type=='read') {
                $lengthType = 4;
            }
            DB::beginTransaction();
            try {
                $permission = Permission::whereFidMenu($idpMenu)->whereRaw('RIGHT(name, '.$lengthType.')='."'".$type."'".'')->first();
                $role = Role::whereId($idpRole)->first();
                if ($value == 'false') {
                    $role->revokePermissionTo($permission->name);
                    revokePermissionToUser($idpRole, $permission);
                    addToLog('Revoke permission from Role has been successfully updated');
                    $textMsg = 'Disable permission <strong>' .$type. '</strong> berhasil';
                } else {
                    $role->givePermissionTo($permission->name);
                    assignPermissionToUser($idpRole, $permission);
                    addToLog('Assign permission to Role has been successfully updated');
                    $textMsg = 'Enable permission <strong>' .$type. '</strong> berhasil';
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
            $form = [
                'name' => 'required|max:50',
            ];
            DB::beginTransaction();
            $request->validate($form);
            try {
                if(Role::whereName($request->name)->where('id', '!=', $request->id)->exists()) {
                    addToLog('Data cannot be saved, the same Role name already exists in the system');
                    return jsonResponse(false, 'Gagal menambahkan data, Role yang sama sudah ada pada sistem. Coba gunakan nama role yang berbeda', 200, array('error_code' => 'name_available'));
                }
                //array data
                $data = array(
                    'name' => $request->name,
                    'user_updated' => $userSesIdp
                );
                Role::whereId($request->id)->update($data);
                addToLog('Role has been successfully updated');
                DB::commit();
                return jsonResponse(true, 'Role berhasil diperbarui', 200);
            } catch (Exception $exception) {
                DB::rollBack();
                return jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        }
    }
    /**
     * _storePermissionToRole
     *
     * @param  mixed $idpRole
     * @param  mixed $IdpPermission
     * @param  mixed $value
     * @param  mixed $type
     * @return void
     */
    private function _storePermissionToRole($idpRole, $IdpPermission, $value, $type)
    {
        $lengthType = 6;
        if($type=='read') {
            $lengthType = 4;
        }
        $permission = Permission::whereFidMenu($IdpPermission)->whereRaw('RIGHT(name, '.$lengthType.')='."'".$type."'".'')->first();
        $role = Role::where('id', $idpRole)->first();
        if ($value == 1) {
            if($permission == true) {
                $role->givePermissionTo($permission->name);
                assignPermissionToUser($idpRole, $permission);
            }
        } else {
            if($permission == true) {
                $role->revokePermissionTo($permission->name);
                revokePermissionToUser($idpRole, $permission);
            }
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
            $users = User::selectRaw('users_system.id')
                ->leftJoin('model_has_roles AS b', 'b.model_id', '=', 'users_system.id')
                ->leftJoin('roles AS c', 'c.id', '=', 'b.role_id')
                ->where('b.role_id', $idp)
                ->get();
            if(count($users) > 0) {
                foreach ($users as $user) {
                    $getUser = User::whereId($user->id)->first();
                    $getPermissions = Permission::select('permissions.*')
                        ->leftJoin('role_has_permissions AS b', 'b.permission_id', '=', 'permissions.id')
                        ->where('b.role_id', $idp)
                        ->get();
                    if(count($getPermissions) > 0) {
                        foreach ($getPermissions as $row) {
                            $getUser->revokePermissionTo($row->name);
                        }
                    }
                    $getUser->removeRole($idp);
                }
            }
            Role::whereId($idp)->delete();
            addToLog('Role has been successfully deleted');
            $textMsg = 'Role berhasil dihapus';
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
