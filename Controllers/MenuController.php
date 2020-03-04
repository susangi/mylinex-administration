<?php

namespace Administration\Controllers;

use Administration\Models\Menu;
use Administration\Models\Permission;
use Administration\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $roots = Menu::roots()->get()->pluck('title', 'id');
        return view('Administration::menu.index', compact('roots'));
        $perms = collect([5, 7]);
        $perms2 = collect([8, 9]);

        $root = Menu::create(['title' => 'Administration', 'permissions' => $perms->merge($perms2)]);
        $child1 = $root->children()->create(['title' => 'Permissions', 'permissions' => $perms, 'url' => 'permissions.index']);
        $child2 = $root->children()->create(['title' => 'Users', 'permissions' => $perms2, 'url' => 'users.index']);

        $root = Menu::create(['title' => 'REFDN', 'permissions' => $perms->merge($perms2)]);
        dd(1);

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

        $isParent = (!empty($request->isParent)) ? $request->isParent : false;
        if ($isParent) {
            $root = Menu::firstOrCreate(['title' => $request->title]);
            if ($root->wasRecentlyCreated) {
                return $this->sendResponse($root, 'Menu Created Successfully');
            } else {
                return $this->sendError('Menu Already Exists');
            }
        } else {
            if (!Route::has($request->url)) {
                return $this->sendError('Route Not Found');
            }
            DB::transaction(function () use ($request) {
                $root = Menu::whereId($request->parent_id)->first();
                $permissions = $request->permissions;
                $tags = $request->permission_tags;
                $child = $root->children()->create(['title' => $request->title, 'url' => $request->url]);


                if (!empty($permissions) && sizeof($permissions) > 0) {
                    foreach ($permissions as $permission) {
                        $permission = strtolower($child->title) . ' ' . strtolower($permission);
                        $child->permissions()->save(new Permission(['name' => $permission, 'guard' => 'web']));
                    }
                }

                if (!empty($tags) && sizeof($tags) > 0) {
                    foreach ($tags as $tag) {
                        $permission =  strtolower($tag);
                        $child->permissions()->save(new Permission(['name' => $permission, 'guard' => 'web']));
                    }
                }

            });
            return $this->sendResponse([], 'Menu Created Successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        $isParent = (!empty($request->isParent)) ? $request->isParent : false;
        if ($isParent) {
            $current = Menu::whereTitle($request->title)->first();
            if (!empty($current)) {
                $menu->title = $request->title;
                $menu->save();
                return $this->sendResponse($menu, 'Menu Updated Successfully');
            } else {
                return $this->sendError('Menu Already Exists');
            }
        } else {
            if (!Route::has($request->url)) {
                return $this->sendError('Route Not Found');
            }

            DB::transaction(function () use ($request, $menu) {
                $menu->title = $request->title;
                $menu->url = $request->url;
                $menu->parent_id = $request->parent_id;
                $menu->save();

                $permissions = $request->permissions;
                $tags = $request->permission_tags;

                $menu->permissions()->delete();


                if (!empty($permissions) && sizeof($permissions) > 0) {
                    foreach ($permissions as $permission) {
                        $permission = strtolower($menu->title) . ' ' . strtolower($permission);
                        $menu->permissions()->save(new Permission(['name' => $permission, 'guard' => 'web']));
                    }
                }


                if (!empty($tags) && sizeof($tags) > 0) {
                    foreach ($tags as $tag) {
                        if ($tag != null) {
                            $permission = strtolower($tag);
                            $menu->permissions()->save(new Permission(['name' => $permission, 'guard' => 'web']));
                        }
                    }
                }

            });

            return $this->sendResponse($menu, 'Menu Updated Successfully');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Menu $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return $this->sendResponse('', 'Menu Successfully Deleted');
    }

    public function tableData(Request $request)
    {
        $user = Auth::user();
        $order_by = $request->order;
        $search = $request->search['value'];
        $start = $request->start;
        $length = $request->length;
        $order_by_str = $order_by[0]['dir'];

        $columns = ['id', 'title', 'url', 'parent_id'];
        $order_column = $columns[$order_by[0]['column']];
        $menu = Menu::tableData($order_column, $order_by_str, $start, $length);
        if (is_null($search) || empty($search)) {
            $menu = $menu->get();
            $menu_count = Menu::all()->count();
        } else {
            $menu = $menu->searchData($search)->get();
            $menu_count = $menu->count();
        }

        $data[][] = array();
        $i = 0;
        $edit_btn = null;
        $delete_btn = null;
        $can_edit = ($user->hasPermissionTo('menu edit') || $user->hasAnyRole(['Super Admin','Admin']) ) ? 1 : 0;
        $can_delete = ($user->hasPermissionTo('menu delete') || $user->hasAnyRole(['Super Admin','Admin'])) ? 1 : 0;

        foreach ($menu as $key => $item) {
            if ($can_edit) {
                $edit_btn = "<i class='icon-md icon-pencil mr-3' onclick=\"edit(this)\" data-parent_id='{$item->parent_id}' data-permissions='{$item->permissions()->get()->pluck('name')}' data-id='{$item->id}' data-title='{$item->title}' data-url='{$item->url}'></i>";
            }
            if ($can_delete) {
                $url = "'menu/" . $item->id . "'";
                $delete_btn = "<i class='icon-md icon-trash' onclick=\"FormOptions.deleteRecord(" . $item->id . ",$url,'menuTable')\"></i>";
            }
            $data[$i] = array(
                $item->id,
                $item->title,
                $item->url,
                (!empty($item->parent)) ? $item->parent->title : null,
                $edit_btn . $delete_btn,
            );
            $i++;
        }

        if ($menu_count == 0) {
            $data = [];
        }

        $json_data = [
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($menu_count),
            "recordsFiltered" => intval($menu_count),
            "data" => $data
        ];

        return json_encode($json_data);

    }
}
