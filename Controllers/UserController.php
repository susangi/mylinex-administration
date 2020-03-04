<?php

namespace Administration\Controllers;

use Administration\Models\Role;
use Administration\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all()->pluck('name', 'name');
        return view('Administration::users.index', compact('roles'));
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
        $exist_user = User::whereEmail($request->email)->withTrashed()->first();

        if (!empty($exist_user)) {
            if ($exist_user->trashed()) {
                $exist_user->name = $request->name;
                $exist_user->email = $request->email;
                $exist_user->updated_by = Auth::user()->id;
                $exist_user->save();
                $exist_user->restore();
                return $this->sendResponse($exist_user, 'User successfully added!');
            }
            return $this->sendError('Error', 'User already exits!');
        } else {
            $user = User::create(['name' => $request['name'], 'email' => $request['email'], 'password' => Hash::make($request['password']), 'created_by' => Auth::user()->id]);
            $user->syncRoles($request->role);
            return $this->sendResponse($exist_user, 'User successfully added!');
        }
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
    public function edit(User $user)
    {
        $role = Role::find(Auth::user()->roles[0]->id);
        $permissions = $role->permissions->pluck('name');
        return view('Administration::users.profile', compact('user', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return void
     */
    public function update(Request $request, User $user)
    {
        if ($user->email != $request->email) {
            $existUser = User::whereEmail($request->email)->withTrashed()->exists();
            if ($existUser) {
                return $this->sendError('User already exits!');
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->updated_by = $request->updated_by;
        $user->save();
        return $this->sendResponse($user, 'User Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->sendResponse('', 'User Successfully Deleted');
    }

    public function tableData(Request $request)
    {
        $user = Auth::user();
        $order_by = $request->order;
        $search = $request->search['value'];
        $start = $request->start;
        $length = $request->length;
        $order_by_str = $order_by[0]['dir'];

        $columns = ['id', 'name', 'email', 'role'];
        $order_column = $columns[$order_by[0]['column']];

        $users = User::tableData($order_column, $order_by_str, $start, $length);

        if (is_null($search) || empty($search)) {
            $users = $users->get();
            $user_count = Role::all()->count();
        } else {
            $users = $users->searchData($search)->get();
            $user_count = $users->count();
        }

        $data[][] = array();
        $i = 0;
        $edit_btn = null;
        $delete_btn = null;
        $reset_btn = null;
        $attempts_btn = null;

        $can_reset = ($user->hasPermissionTo('reset password') || $user->hasAnyRole(['Super Admin', 'Admin'])) ? 1 : 0;
        $can_edit = ($user->hasPermissionTo('users edit') || $user->hasAnyRole(['Super Admin', 'Admin'])) ? 1 : 0;
        $can_delete = ($user->hasPermissionTo('users delete') || $user->hasAnyRole(['Super Admin', 'Admin'])) ? 1 : 0;
        $reset_attempts = ($user->hasPermissionTo('reset attempts') || $user->hasAnyRole(['Super Admin', 'Admin'])) ? 1 : 0;

        foreach ($users as $key => $user) {
            if ($reset_attempts) {
                if ($user->login_attempts>3){
                    $attempts_btn = "<i title='Unlock user' class='icon-md icon-lock-open mr-3' onclick=\"resetAttempt(this)\" data-id='{$user->id}'></i>";
                }
            }

            if ($can_reset) {
                $reset_btn = "<i title='Reset password' class='icon-md icon-action-undo mr-3' onclick=\"reset(this)\" data-id='{$user->id}'></i>";
            }
            if ($can_edit) {
                $edit_btn = "<i title='Edit user' class='icon-md icon-pencil mr-3' onclick=\"edit(this)\" data-id='{$user->id}' data-email='{$user->email}' data-name='{$user->name}' data-roles='{$user->getRoleNames()[0]}'></i>";
            }
            if ($can_delete) {
                $url = "'users/" . $user->id . "'";
                $delete_btn = "<i title='Delete user' class='icon-md icon-trash mr-3' onclick=\"FormOptions.deleteRecord(" . $user->id . ",$url,'userTable')\"></i>";
            }

            $roles = $user->roles;

            $data[$i] = array(
                $user->name,
                $user->email,
                $user->getRoleNames(),
                $edit_btn . $delete_btn . $reset_btn . $attempts_btn
            );
            $i++;
        }

        if ($user_count == 0) {
            $data = [];
        }

        $json_data = [
            "draw" => intval($_REQUEST['draw']),
            "recordsTotal" => intval($user_count),
            "recordsFiltered" => intval($user_count),
            "data" => $data
        ];

        return json_encode($json_data);
    }

    public function resetPassword(Request $request, User $user)
    {
        $newPassword = $request->password;
        $user->password = Hash::make($newPassword);
        $user->save();
        return $this->sendResponse($user, 'Password Reset Successfully');
    }


    public function updatePrimaryData(Request $request, User $user)
    {
        if ($user->email != $request->email) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|unique:users'
            ]);
        }

        if (!empty($request->image)) {
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|max:255',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->with(['alert-type' => 'error', 'message' => $validator->errors()])
                ->withInput();
        }
        $imageName = null;
        if (!empty($request->image)) {
            $imageName = $user->id . '.' . request()->image->getClientOriginalExtension();
            if (file_exists(public_path() . '/images/' . $imageName)) {
                File::delete(public_path() . '/images/' . $imageName);
            }
            request()->image->move(public_path('images/profile/'), $imageName);
        }

        $user->image = $imageName;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->save();

        return redirect()
            ->back()
            ->with(['alert-type' => 'success', 'message' => 'User Account Updated Successfully '])
            ->withInput();

    }

    public function updatePassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->with(['alert-type' => 'error', 'message' => $validator->errors()])
                ->withInput();
        }

        $newPassword = $request->password;
        $user->password = Hash::make($newPassword);
        $user->save();
        return redirect()
            ->back()
            ->with(['alert-type' => 'success', 'message' => 'Password Changed Successfully '])
            ->withInput();

    }

    public function unlock(Request $request, User $user)
    {
        $user->login_attempts = 0;
        $user->save();
        return $this->sendResponse($user, 'User Unlocked Successfully');

    }
}
