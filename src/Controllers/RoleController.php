<?php

namespace Administration\Controllers;


use Administration\Models\Permission;
use Administration\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all()->pluck('name', 'name');
        return view('Administration::role.index', compact('permissions'));
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
        $permissions = $request->permissions;
        $role = Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        $role = \Spatie\Permission\Models\Role::find($role->id);
        $role->givePermissionTo($permissions);
        $msg = ($role->wasRecentlyCreated) ? 'Role Created Successfully' : 'Role Already Exists';
        return $this->sendResponse($role, $msg);
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
     * @param Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $role->name = $request->name;
        $permissions = $request->permissions;
        $role->save();

        $role = \Spatie\Permission\Models\Role::find($role->id);
        $currentPermissions= $role->getAllPermissions();
        $role->revokePermissionTo($currentPermissions);
        $role->givePermissionTo($permissions);
        return $this->sendResponse($role, 'Role Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return $this->sendResponse('', 'Role Successfully Deleted');

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

        $roles = Role::tableData($order_column, $order_by_str, $start, $length);
        if (is_null($search) || empty($search)) {
            $roles = $roles->get();
            $roles_count = Role::all()->count();
        } else {
            $roles = $roles->searchData($search)->get();
            $roles_count = $roles->count();
        }

        $data[][] = array();
        $i = 0;
        $edit_btn = null;
        $delete_btn = null;
        $can_edit = ($user->hasPermissionTo('roles edit') || $user->hasAnyRole(['Super Admin','Admin'])) ? 1 : 0;
        $can_delete = ($user->hasPermissionTo('roles delete') || $user->hasAnyRole(['Super Admin','Admin'])) ? 1 : 0;

        foreach ($roles as $key => $role) {
            if ($can_edit) {
                $edit_btn = "<i class='icon-md icon-pencil mr-3' onclick=\"editPermission(this)\" data-id='{$role->id}' data-name='{$role->name}' data-permissions='{$role->permissions->pluck('name')}'></i>";
            }
            if ($can_delete) {
                $url = "'roles/" . $role->id . "'";
                $delete_btn = "<i class='icon-md icon-trash' onclick=\"FormOptions.deleteRecord(" . $role->id . ",$url,'roleTable')\"></i>";
            }

            $permissions=[];

            $role = \Spatie\Permission\Models\Role::find($role->id);
            $permissions_list = $role->getAllPermissions();

            foreach ($permissions_list->pluck('name') as $permission) {
//                $p = '<span class="badge badge-indigo mt-15 mr-10">'.$permission.'</span>';
                array_push($permissions, $permission . ' ');
            }

            $data[$i] = array(
                $role->name,
                $permissions,
                $role->guard_name,
                $edit_btn . $delete_btn
            );
            $i++;
        }

        if ($roles_count == 0) {
            $data = [];
        }

        $json_data = [
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($roles_count),
            "recordsFiltered" => intval($roles_count),
            "data" => $data
        ];

        return json_encode($json_data);
    }

    public function renderForm(Request $request)
    {
        $id = $request->id;
        $role = \Spatie\Permission\Models\Role::findById($id);

        $permissions = $role->getAllPermissions();
        $rolePermissions = collect($permissions->pluck('name'));
        $view = View::make('Administration::role.permissions-list', compact('rolePermissions'));
        $html = $view->render();
        return $html;
    }
}
