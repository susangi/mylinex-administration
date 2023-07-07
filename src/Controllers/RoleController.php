<?php

namespace Administration\Controllers;


use Administration\Models\Permission;
use Administration\Models\Role;
use Administration\Repositories\RoleRepository;
use Administration\Requests\RoleStoreRequest;
use Administration\Requests\RoleUpdateRequest;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;


class RoleController extends Controller
{

    public function __construct(private RoleRepository $repository)
    {
    }

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
     * @param \Illuminate\Http\RoleStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleStoreRequest $request)
    {
        try {
            $role = $this->repository->store($request->validated());

            return $this->sendResponse
            (
                $role,
                ($role->wasRecentlyCreated) ? 'Role created successfully.' : 'Role already exists.'
            );
        } catch (Exception $exception) {
            return $this->sendError(config('exception-message.system-error'));
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\RoleUpdateRequest $request
     * @param Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        try {
            $role = $this->repository->update($request->validated(), $role);

            return $this->sendResponse($role, 'Role updated successfully.');
        } catch (Exception $exception) {
            return $this->sendError(config('exception-message.system-error'));
        }
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
        try {
            $this->repository->destroy($role);

            return $this->sendResponse('', 'Role deleted successfully.');
        } catch (Exception $exception) {
            return $this->sendError(config('exception-message.system-error'));
        }
    }

    public function tableData(Request $request)
    {
        try {
            $tableData = $this->repository->tableData($request);

            return json_encode($tableData);
        } catch (Exception $exception) {
            return $this->sendError(config('exception-message.system-error'));
        }
    }

    public function renderForm(Request $request)
    {
        try {
            $rolePermissions = $this->repository->getPermissions($request->all());
            $view = View::make('Administration::role.permissions-list', compact('rolePermissions'));

            return $view->render();
        } catch (Exception $exception) {
            return $this->sendError(config('exception-message.system-error'));
        }

    }
}
