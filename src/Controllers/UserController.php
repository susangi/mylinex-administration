<?php

namespace Administration\Controllers;

use Administration\Models\Menu;
use Administration\Models\Permission;
use Administration\Models\Role;
use Administration\Models\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Administration\Services\PasswordPolicyService;
use Mockery\Exception;

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
        $type = env('APP_TYPE');
        $menu = Menu::query();
        if ($type == 'ALL') {
            $menu->where('type', '=', 'SMSFW')->orWhere('type', '=', 'VOICEFW');
        } else {
            $menu->where('type', '=', $type);
        }

        $landing_page = $menu->where('url', '<>', null)->get()->pluck('title', 'id');

        return view('Administration::users.index', compact('roles', 'landing_page'));
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

        $is_api = $request->is_api == 'on' ? 1 : 0;
        if (User::whereName($request->name)->withTrashed()->count() > 0) {
            return $this->sendError('User name already exists!');
        }

        $exist_user = User::whereEmail($request->email)->withTrashed()->first();
        if (!empty($exist_user)) {
            if (User::whereName($request->name)->count() > 0) {
                return $this->sendError('User name already exists!');
            }
            if ($exist_user->trashed()) {
                $exist_user->name = $request->name;
                $exist_user->updated_by = Auth::user()->id;
                $exist_user->landing_page = $request->landing_page;
                $exist_user->is_api = $is_api;
                $exist_user->save();
                $exist_user->syncRoles($request->role);
                $exist_user->restore();

                $pc = new PasswordPolicyService($exist_user);
                $pc->passwordChangeProcess($request->password);

                return $this->sendResponse($exist_user, 'User successfully added!');
            }
            return $this->sendError('Error', 'User already exits!');
        } else {
            $user = User::createUser($request->validate(), $is_api);

            $pc = new PasswordPolicyService($user);
            $pc->passwordChangeProcess($request['password']);

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
        $is_api = $request->is_api == 'on' ? 1 : 0;
        if (User::whereName($request->name)->where('id', '!=', $user->id)->withTrashed()->count() > 0) {
            return $this->sendError('User name already exists!');
        }

        if ($user->email != $request->email) {
            $existUser = User::whereEmail($request->email)
                ->withTrashed()
                ->exists();
            if ($existUser) {
                return $this->sendError('User already exits!');
            }
        }

        $user->updated_by = $request->updated_by;
        $user->landing_page = $request->landing_page;
        $user->is_api = $is_api;
        $user->save();
        $user->syncRoles($request->role);

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

        $columns = ['id', 'name', 'email', 'id'];
        $order_column = $columns[$order_by[0]['column']];

        $users = User::tableData($order_column, $order_by_str, $start, $length);

        if (is_null($search) || empty($search)) {
            $users = $users->get();
            $user_count = User::all()->count();
        } else {
            $users = $users->searchData($search)->get();
            $user_count = $users->count();
        }

        $data[][] = array();
        $i = 0;
        $edit_btn = null;
        $delete_btn = null;
        $reset_btn = null;
        $can_reset = ($user->can('reset password')) ? 1 : 0;
        $can_edit = ($user->can('users edit')) ? 1 : 0;
        $can_delete = ($user->can('users delete')) ? 1 : 0;
        $reset_attempts = ($user->can('reset attempts')) ? 1 : 0;
        foreach ($users as $key => $user) {
            $attempts_btn = null;

            $menu_title = (!empty($user->landing_page)) ? Menu::where('id', '=', $user->landing_page)->first()->title : '-';

            if ($reset_attempts) {
                $last_login = new Carbon(($user->last_login) ? $user->last_login : $user->created_at);
                $disabledUser = Carbon::now()->diffInDays($last_login) >= config('auth.user_expires_days');
                if ($user->login_attempts >= 3 || $disabledUser) {
                    $attempts_btn = "<i title='Unlock user' class='icon-md icon-lock-open mr-3' onclick=\"resetAttempt(this)\" data-id='{$user->id}'></i>";
                }
            }

            if ($can_reset) {
                $reset_btn = "<i class='icon-md icon-action-undo  mr-3' onclick=\"reset(this)\" data-id='{$user->id}'></i>";
            }
            if ($can_edit) {
                $edit_btn = "<i class='icon-md icon-pencil mr-3' onclick=\"edit(this)\" data-id='{$user->id}' data-email='{$user->email}' data-name='{$user->name}' data-landing_page='{$user->landing_page}' data-roles='{$user->getRoleNames()}' data-is_api='{$user->is_api}'></i>";
            }
            if ($can_delete) {
                $url = "'users/" . $user->id . "'";
                $delete_btn = "<i class='icon-md icon-trash mr-3' onclick=\"FormOptions.deleteRecord(" . $user->id . ",$url,'userTable')\"></i>";
            }

            $roles = $user->roles;

            $api_user = ($user->is_api) ? "<i class='text-success fa fa-check'></i>" : "<i class='fa fa-close text-danger'>";
            $data[$i] = array(
                $user->id,
                $user->name,
                $user->email,
                $user->getRoleNames(),
                $menu_title,
                $api_user,
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
        if (Carbon::now()->diffInSeconds($user->password_changed_at) <= config('auth.seconds_for_day')) {
            return $this->sendResponse($user, 'It has not been 24 hours since the password was changed');
        }

        $newPassword = $request->password;
        $user->password = Hash::make($newPassword);
        $user->password_changed_at = Carbon::now()->toDateTimeString();
        $user->last_login = Carbon::now();
        $user->save();

        $pc = new PasswordPolicyService($user);
        $pc->passwordChangeProcess($request->password);

        return $this->sendResponse($user, 'Password Reset Successfully');
    }

    public function unlock(Request $request, User $user)
    {
        try {
            User::unlockUserAccount($user);
            return $this->sendResponse($user, 'User Unlocked Successfully');
        } catch (Exception $exception) {
            return $this->sendError(config('exception-message.system-error'));
        }
    }

    public function recentPasswordValid(Request $request)
    {
        $password = $request->password;
        $user = Auth::user();
        if (isset($request->user_id) && !empty($request->user_id)) {
            $user = User::find($request->user_id);
        }
        $passwordPolicyService = new PasswordPolicyService($user);
        $is_recently_used = $passwordPolicyService->isRecentlyUsedPassword($password);
        if (!$is_recently_used) {
            return 'true';
        }
        return 'false';
    }

    public function updatePassword(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->with(['alert-type' => 'error', 'message' => $validator->errors()])
                ->withInput();
        }

        if (Carbon::now()->diffInSeconds($user->password_changed_at) <= config('auth.seconds_for_day')) {
            return redirect()
                ->back()
                ->with(['alert-type' => 'error', 'message' => 'It has not been 24 hours since the password was changed']);
        }

        $newPassword = $request->password;
        $user->password = Hash::make($newPassword);
        $user->password_changed_at = Carbon::now()->toDateTimeString();
        $user->save();

        $pc = new PasswordPolicyService($user);
        $pc->passwordChangeProcess($newPassword);

        return redirect()
            ->back()
            ->with(['alert-type' => 'success', 'message' => 'Password Changed Successfully '])
            ->withInput();

    }
}
