<?php

namespace Administration\Controllers;

use Administration\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Administration::permission.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->name;
        $permission = Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        $msg = ($permission->wasRecentlyCreated) ? 'Permission Created Successfully' : 'Permission Already Exists';
        return $this->sendResponse($permission, $msg);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $permission->name = $request->name;
        $permission->save();

        return $this->sendResponse($permission, 'Permission Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Permission $permission
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return $this->sendResponse('', 'Permission Successfully Deleted');
    }

    public function tableData(Request $request)
    {
        $user = Auth::user();
        $order_by = $request->order;
        $search = $request->search['value'];
        $start = $request->start;
        $length = $request->length;
        $order_by_str = $order_by[0]['dir'];

        $columns = ['id', 'name', 'guard_name'];
        $order_column = $columns[$order_by[0]['column']];
        $permissions = Permission::tableData($order_column, $order_by_str, $start, $length);
        if (is_null($search) || empty($search)) {
            $permissions = $permissions->get();
            $permissions_count = Permission::all()->count();
        } else {
            $permissions = $permissions->searchData($search)->get();
            $permissions_count = $permissions->count();
        }

        $data[][] = array();
        $i = 0;
        $edit_btn = null;
        $delete_btn = null;
        $can_edit = ($user->hasPermissionTo('permissions edit') || $user->hasAnyRole(['Super Admin','Admin'])) ? 1 : 0;
        $can_delete = ($user->hasPermissionTo('permissions delete') || $user->hasAnyRole(['Super Admin','Admin'])) ? 1 : 0;

        foreach ($permissions as $key => $permission) {
            if ($can_edit) {
                $edit_btn = "<i class='icon-md icon-pencil mr-3' onclick=\"editPermission(this)\" data-id='{$permission->id}' data-name='{$permission->name}'></i>";
            }
            if ($can_delete) {
                $url ="'permissions/".$permission->id."'";
                $delete_btn = "<i class='icon-md icon-trash' onclick=\"FormOptions.deleteRecord(" . $permission->id . ",$url,'permissionTable')\"></i>";
            }
            $data[$i] = array(
                $permission->name,
                $permission->guard_name,
                $edit_btn . $delete_btn
            );
            $i++;
        }

        if ($permissions_count == 0) {
            $data = [];
        }

        $json_data = [
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($permissions_count),
            "recordsFiltered" => intval($permissions_count),
            "data" => $data
        ];

        return json_encode($json_data);
    }
}
