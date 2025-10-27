<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait Select2Common
{
    /**
     * select2_permission
     *
     * @return void
     */
    protected function select2_permission($params)
    {
        // Search term
        $searchTerm = strtolower($params['search']);
        $page = $params['page'];

        $result = array();
        $query = DB::table('permission_has_menus AS a')
            ->selectRaw("a.id, a.name AS text, a.parent_id AS parent")
            ->leftJoin('permissions AS b', 'b.fid_menu', '=', 'a.id');
        $qCountRes = DB::table('permission_has_menus');
        if(isset($params['role'])) {
            $role = $params['role'];
            $permissionsId = DB::table('permission_has_menus AS a')
                ->selectRaw("a.id")
                ->leftJoin('permissions AS b', 'b.fid_menu', '=', 'a.id')
                ->leftJoin('role_has_permissions AS c', 'c.permission_id', '=', 'b.id')
                ->leftJoin('roles AS d', 'd.id', '=', 'c.role_id')
                ->where('c.role_id', $role)
                ->where('a.has_child', 'N')
                // ->where('a.has_child', 'N')
                ->groupBy('a.id')
                ->orderBy('a.order_line', 'ASC')
                ->pluck('id');

            $query = $query->whereNotIn('a.id', $permissionsId);
            $qCountRes = $qCountRes->whereNotIn('id', $permissionsId);
        } if(isset($params['child'])) {
            $child = $params['child'];
            $query = $query->where('a.has_child', $child);
            $qCountRes = $qCountRes->where('has_child', $child);
        }

        $query = $query->whereRaw("LOWER(a.name) LIKE '%' || ? || '%'", [$searchTerm]);
        $countResult = $qCountRes->whereRaw("LOWER(name) LIKE '%' || ? || '%'", [$searchTerm])->count();
        $start=0;
        $limit=20;
        if($page!=''){
            $start=20*$page-20;
            $limit=20;
            $getResult = $query->offset($start)->limit($limit)->groupBy('a.id')->orderBy('a.order_line', 'ASC');
        }else{
            $getResult = $query->groupBy('a.id')->orderBy('a.order_line', 'ASC');
        }

        $getArray = $getResult->get()->toArray();
        $result['results'] = $getArray;
        $pagination = array("more" => true);
        if($countResult < 20 ){
            $pagination = array("more" => false);
        }
        $result['pagination'] = $pagination;
        $result['count'] = $countResult;
        return $result;
    }
}
