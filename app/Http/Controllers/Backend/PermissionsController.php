<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Menus;
use App\Models\User;
use App\Traits\Select2Common;
use App\Traits\SiteCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{
    use SiteCommon;
    use Select2Common;

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('direct_permission:kelola-pengguna-permissions-read', only: ['index', 'show']),
            new Middleware('direct_permission:kelola-pengguna-permissions-create', only: ['store']),
            new Middleware('direct_permission:kelola-pengguna-permissions-update', only: ['update']),
            new Middleware('direct_permission:kelola-pengguna-permissions-delete', only: ['delete']),
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
            'title' => 'Kelola User Permissions',
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
            config('app.env') === 'production' ? 'dist/scripts/backend/manage_permissions.init.min.js' : 'dist/scripts/backend/manage_permissions.init.js'
        );

        addToLog('Mengakses halaman ' .$data['title']. ' - Backend');
        return view('backend.manage_permissions', compact('data'));
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
            if(isset($request->is_parentpermissions)) {
                $params = [
                    'search' => $request->search,
                    'page' => $request->page,
                    'child' => 'Y'
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
        } else if(isset($request->idp)){
            try {
                $getRow = Menus::whereId($request->idp)->first();
                if($getRow != null){
                    //Parent Custom
                    $getRow->parent = null;
                    if($getRow->parent_id != null || $getRow->parent_id != '') {
                        $getParent = Menus::whereId($getRow->parent_id)
                        ->first();
                        if($getParent==true) {
                            $getRow->parent = $getParent;
                        }
                    }
                    //Crud Custom
                    $getRow->create = 0;
                    $getRow->read = 0;
                    $getRow->update = 0;
                    $getRow->delete = 0;
                    $getCrud = Permission::whereFidMenu($getRow->id)
                        ->get();
                    $getRow->crud = $getCrud;
                    if($getCrud) {
                        foreach ($getCrud as $crud) {
                            if(substr($crud->name, strrpos($crud->name, "-") + 1)=='create') {
                                $getRow->create = 1;
                            } if(substr($crud->name, strrpos($crud->name, "-") + 1)=='read') {
                                $getRow->read = 1;
                            } if(substr($crud->name, strrpos($crud->name, "-") + 1)=='update') {
                                $getRow->update = 1;
                            } if(substr($crud->name, strrpos($crud->name, "-") + 1)=='delete') {
                                $getRow->delete = 1;
                            }
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
            $data = Menus::orderBy('order_line', 'DESC')->get();
            $output = Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row){
                    $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editPermission('."'".$row->id."'".');"><i class="la la-edit fs-3"></i></button>';
                    $btnDelete = '';
                    if(Auth::user()->can('kelola-pengguna-permissions-delete')){
                        $btnDelete = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-danger mb-1 ms-1" data-bs-toggle="tooltip" title="Hapus data!" onclick="_deletePermission('."'".$row->id."'".');"><i class="las la-trash-alt fs-3"></i></button>';
                    }
                    return $btnEdit.$btnDelete;
                })
                ->editColumn('icon', function ($row) {
                    $iconCustom = '-';
                    if($row->icon!=null || $row->icon!='') {
                        $iconCustom = '<span class="badge badge-light"><i class="'.$row->icon.' fs-2x text-dark"></i></span>';
                    }
                    return $iconCustom;
                })
                ->editColumn('name', function ($row) {
                    $nameCustom = $row->name;
                    if($row->parent_id!=null || $row->parent_id!='') {
                        $nameCustom = '- ' .$row->name;
                    }
                    return $nameCustom;
                })
                ->editColumn('route_name', function ($row) {
                    $routeCustom = '-';
                    if($row->has_route=='Y' || $row->has_route=='Y') {
                        $routeCustom = $row->route_name;
                    }
                    return $routeCustom;
                })
                ->addColumn('parent', function ($row) {
                    $parentCustom = '-';
                    if($row->parent_id != null || $row->parent_id != '') {
                        $getParent = DB::table('permission_has_menus')
                        ->whereId($row->parent_id)
                        ->first();
                        $parentCustom = $getParent->name;
                    }
                    return $parentCustom;
                })
                ->addColumn('crud', function ($row) {
                    $crudCustom = '';
                    $getCrud = Permission::whereFidMenu($row->id)
                        ->get();
                    if($getCrud) {
                        foreach ($getCrud as $crud) {
                            $crudCustom .= '<span class="badge badge-light-success me-1 mb-1">'.substr($crud->name, strrpos($crud->name, "-") + 1).'</span>';
                        }
                    }
                    return $crudCustom;
                })
                ->rawColumns(['action', 'icon', 'route_name', 'parent', 'crud'])
                ->make(true);

            return $output;
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
            'name' => 'required|max:50',
            'order_line' => 'required|max:12'
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            if(isset($request->has_parent)) {
                if(Menus::whereName($request->name)->whereParentId($request->cbo_parent)->exists()) {
                    addToLog('Data cannot be saved, the same Menu/ Permission name already exists in the system');
                    return jsonResponse(false, 'Gagal menambahkan data, Menu/ Permission yang sama sudah ada pada sistem. Coba gunakan nama menu/ permission yang berbeda', 200, array('error_code' => 'name_available'));
                }
            } else {
                if(Menus::whereName($request->name)->whereNull('parent_id')->exists()) {
                    addToLog('Data cannot be saved, the same Menu/ Permission name already exists in the system');
                    return jsonResponse(false, 'Gagal menambahkan data, Menu/ Permission yang sama sudah ada pada sistem. Coba gunakan nama menu/ permission yang berbeda', 200, array('error_code' => 'name_available'));
                }
            }
            //array data
            $data = array(
                'name' => $request->name,
                'icon' => isset($request->icon) ? $request->icon : NULL,
                'has_route' => isset($request->has_route) ? 'Y' : 'N',
                'route_name' => isset($request->has_route) || $request->route_name !='' ? $request->route_name : NULL,
                'parent_id' => isset($request->has_parent) || $request->cbo_parent !='' ? $request->cbo_parent : NULL,
                'has_child' => isset($request->has_child) ? 'Y' : 'N',
                'is_crud' => isset($request->is_crud) ? 'Y' : 'N',
                'order_line' => $request->order_line,
                'user_add' => $userSesIdp
            );
            $insertPermissionMenu = Menus::insertGetId($data);
            addToLog('Permission menu has been successfully added');
            //If Crud or Not
            $nameSlug = isset($request->has_parent) || $request->cbo_parent !='' ? Str::slug(Menus::whereId($request->cbo_parent)->first()->name.'-'.$request->name) : Str::slug($request->name);
            if(isset($request->is_crud)) {
                if(isset($request->create)) {
                    $this->store_crudpermission('', $nameSlug.'-create', $insertPermissionMenu);
                } if(isset($request->read)) {
                    $this->store_crudpermission('', $nameSlug.'-read', $insertPermissionMenu);
                } if(isset($request->update)) {
                    $this->store_crudpermission('', $nameSlug.'-update', $insertPermissionMenu);
                } if(isset($request->delete)) {
                    $this->store_crudpermission('', $nameSlug.'-delete', $insertPermissionMenu);
                }
            } else {
                $this->store_crudpermission('', $nameSlug.'-read', $insertPermissionMenu);
            }
            DB::commit();
            return jsonResponse(true, 'Menu/ Permission berhasil ditambahkan', 200);
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
        $form = [
            'name' => 'required|max:50',
            'order_line' => 'required|max:12'
        ];
        DB::beginTransaction();
        $request->validate($form);
        try {
            if(isset($request->has_parent)) {
                // if(Menus::whereName($request->name)->where('id', '!=', $request->id)->whereNull('parent_id')->exists() || Menus::whereName($request->name)->where('id', '!=', $request->id)->whereParentId($request->cbo_parent)->exists()) {
                if(Menus::whereName($request->name)->where('id', '!=', $request->id)->whereParentId($request->cbo_parent)->exists()) {
                    addToLog('Data cannot be saved, the same Menu/ Permission name already exists in the system');
                    return jsonResponse(false, 'Gagal memperbarui data, Menu/ Permission yang sama sudah ada pada sistem. Coba gunakan nama menu/ permission yang berbeda', 200, array('error_code' => 'name_available'));
                }
            } else {
                if(Menus::whereName($request->name)->where('id', '!=', $request->id)->whereNull('parent_id')->exists()) {
                    addToLog('Data cannot be saved, the same Menu/ Permission name already exists in the system');
                    return jsonResponse(false, 'Gagal memperbarui data, Menu/ Permission yang sama sudah ada pada sistem. Coba gunakan nama menu/ permission yang berbeda', 200, array('error_code' => 'name_available'));
                }
            }
            //array data
            $data = array(
                'name' => $request->name,
                'icon' => isset($request->icon) ? $request->icon : NULL,
                'has_route' => isset($request->has_route) ? 'Y' : 'N',
                'route_name' => isset($request->has_route) || $request->route_name !='' ? $request->route_name : NULL,
                'parent_id' => isset($request->has_parent) || $request->cbo_parent !='' ? $request->cbo_parent : NULL,
                'has_child' => isset($request->has_child) ? 'Y' : 'N',
                'is_crud' => isset($request->is_crud) ? 'Y' : 'N',
                'order_line' => $request->order_line,
                'user_updated' => $userSesIdp
            );
            Menus::whereId($request->id)->update($data);
            addToLog('Permission menu has been successfully updated');
            //If Crud or Not
            $nameSlug = isset($request->has_parent) || $request->cbo_parent !='' ? Str::slug(Menus::whereId($request->cbo_parent)->first()->name.'-'.$request->name) : Str::slug($request->name);
            // $oldNameSlug = Str::slug($request->old_name);
            $oldParent = Menus::whereId($request->old_parent)->first();
            $oldParentName = isset($oldParent) ? $oldParent->name : '';
            $oldNameSlug = $request->old_parent !='' ? Str::slug($oldParentName.'-'.$request->old_name) : Str::slug($request->old_name);
            if(isset($request->is_crud)) {
                //Create
                if(isset($request->create)) {
                    $this->store_crudpermission($oldNameSlug.'-create', $nameSlug.'-create', $request->id);
                } else {
                    if(Permission::where('fid_menu', $request->id)->whereRaw('RIGHT(name, 6)='."'create'".'')->exists()) {
                        Permission::where('fid_menu', $request->id)->whereRaw('RIGHT(name, 6)='."'create'".'')->delete();
                    }
                }
                //Read
                if(isset($request->read)) {
                    $this->store_crudpermission($oldNameSlug.'-read', $nameSlug.'-read', $request->id);
                } else {
                    if(Permission::where('fid_menu', $request->id)->whereRaw('RIGHT(name, 4)='."'read'".'')->exists()) {
                        Permission::where('fid_menu', $request->id)->whereRaw('RIGHT(name, 4)='."'read'".'')->delete();
                    }
                }
                //Update
                if(isset($request->update)) {
                    $this->store_crudpermission($oldNameSlug.'-update', $nameSlug.'-update', $request->id);
                } else {
                    if(Permission::where('fid_menu', $request->id)->whereRaw('RIGHT(name, 6)='."'update'".'')->exists()) {
                        Permission::where('fid_menu', $request->id)->whereRaw('RIGHT(name, 6)='."'update'".'')->delete();
                    }
                }
                //Delete
                if(isset($request->delete)) {
                    $this->store_crudpermission($oldNameSlug.'-delete', $nameSlug.'-delete', $request->id);
                } else {
                    if(Permission::where('fid_menu', $request->id)->whereRaw('RIGHT(name, 6)='."'delete'".'')->exists()) {
                        Permission::where('fid_menu', $request->id)->whereRaw('RIGHT(name, 6)='."'delete'".'')->delete();
                    }
                }
            } else {
                $permissions = Permission::where('fid_menu', $request->id)->get();
                if(count($permissions) > 1) {
                    if(Permission::where('fid_menu', $request->id)->whereRaw('RIGHT(name, 6)='."'create'".'')->exists()) {
                        Permission::where('fid_menu', $request->id)->whereRaw('RIGHT(name, 6)='."'create'".'')->delete();
                    } if(Permission::where('fid_menu', $request->id)->whereRaw('RIGHT(name, 6)='."'update'".'')->exists()) {
                        Permission::where('fid_menu', $request->id)->whereRaw('RIGHT(name, 6)='."'update'".'')->delete();
                    } if(Permission::where('fid_menu', $request->id)->whereRaw('RIGHT(name, 6)='."'delete'".'')->exists()) {
                        Permission::where('fid_menu', $request->id)->whereRaw('RIGHT(name, 6)='."'delete'".'')->delete();
                    }
                }
                $this->store_crudpermission($oldNameSlug.'-read', $nameSlug.'-read', $request->id);
            }
            DB::commit();
            return jsonResponse(true, 'Data menu/ permission berhasil diperbarui', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * store_crudpermission
     *
     * @param  mixed $name
     * @param  mixed $fid_menu
     * @return void
     */
    private function store_crudpermission($oldName, $name, $fid_menu) {
        $userSesIdp = Auth::user()->id;
        DB::beginTransaction();
        try {
            $data = array(
                'name' => $name,
                'fid_menu' => $fid_menu,
                'guard_name' => 'web'
            );
            if($oldName == '' || !Permission::where('name', $oldName)->exists()) {
                $data['user_add'] = $userSesIdp;
                Permission::insert($data);
            } else {
                $data['user_updated'] = $userSesIdp;
                Permission::where('name', $oldName)->update($data);
            }
            DB::commit();
            Artisan::call("cache:forget spatie.permission.cache");
            Artisan::call("cache:clear");
        } catch (Exception $exception) {
            // \dd($exception);
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
            $countChild = Menus::whereParentId($idp)->count();
            if($countChild > 0) {
                addToLog('Data cannot be deleted. Permission data has children, Please move/delete the child first then delete this menu again');
                return jsonResponse(false, 'Gagal menghapus data. Menu/ permission memiliki sub, pindahkan/ hapus sub permission terlebih dahulu kemudian hapus permission ini kembali', 200, array('error_code' => 'has_parent'));
            }
            $users = User::get();
            if($users) {
                foreach ($users as $user) {
                    $this->_revokePermissionsUser($user, $idp);
                }
            }
            //Delete Has Menu
            Menus::whereId($idp)->delete();
            addToLog('Delete permission has been successfully');
            DB::commit();
            return jsonResponse(true, 'Data menu/ permission berhasil dihapus', 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * _revokePermissionsUser
     *
     * @param  mixed $user
     * @param  mixed $permissionMenu
     * @return void
     */
    private function _revokePermissionsUser($user, $permissionMenu) {
        $permissions = Permission::whereFidMenu($permissionMenu)->get();
        if($permissions) {
            foreach ($permissions as $permission) {
                $user->revokePermissionTo($permission->name);
                $this->_revokePermissionRoles($permission->name);
                Permission::whereId($permission->id)->delete();
            }
        }
    }
    /**
     * _revokePermissionRoles
     *
     * @param  mixed $user
     * @param  mixed $permissionMenu
     * @return void
     */
    private function _revokePermissionRoles($permissionMenu) {
        $roles = Role::get();
        if($roles) {
            foreach ($roles as $role) {
                $role->revokePermissionTo($permissionMenu);
            }
        }
    }
}
