<?php

namespace Tests\Unit;

use Administration\Repositories\RoleRepository;
use Administration\Traits\AuthenticationHelperTrait;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Administration\Models\Role;
use Tests\TestCase;

class RoleManagementTest extends TestCase
{
    use AuthenticationHelperTrait,
        RefreshDatabase;

    private $user = [];
    private $formData = [];
    private $permission;
    private $newRole;
    private RoleRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createSuperUser();
        $this->be($this->user);
        $this->permission = Permission::firstOrCreate(['name' => 'permission', 'guard_name' => 'web']);
        $this->permission = Permission::firstOrCreate(['name' => 'permission1', 'guard_name' => 'web']);
        $this->formData = [
            'name' => 'Role',
            'permissions' => [
                Permission::orderBy('id', 'asc')->first()->name
            ]
        ];
        $this->repository = new RoleRepository();
    }

    /**
     * @test
     * new role create successfully
     * create role
     * add permissions
     *
     * @return void
     */
    public function if_user_can_create_a_role_successfully()
    {
        $this->repository->store($this->formData);
        $role = Role::where('name', $this->formData['name'])->first();

        $this->assertDatabaseHas('roles', ['name' => $this->formData['name']]);
        $this->assertTrue($role->hasPermissionTo($this->formData['permissions'][0]));
    }

    /**
     * @test
     * new role can not be created using null data property.
     *
     * @return void
     */
    public function if_new_role_can_not_be_created_using_null_data_property()
    {
        $this->expectException(QueryException::class);
        $this->repository->store(['name' => null, 'permissions' => null]);
    }

    /**
     * @test
     * role update successfully
     * update role and permissions
     *
     * @return void
     */
    public function if_user_can_update_a_role_successfully()
    {
        $this->repository->store($this->formData);
        $role = Role::orderBy('id', 'asc')->first();
        $permission = Permission::orderBy('id', 'desc')->first();
        $this->formData = array_merge($this->formData, ['name' => 'Test', 'permissions' => [$permission->name]]);
        $this->repository->update($this->formData, $role);
        $role = Role::where('name', $this->formData['name'])->first();

        $this->assertDatabaseHas('roles', ['name' => $this->formData['name']]);
        $this->assertTrue($role->hasPermissionTo($this->formData['permissions'][0]));
    }

    /**
     * @test
     * role deleted successfully
     *
     * @return void
     */
    public function if_user_can_delete_a_role_successfully()
    {
        $this->repository->store($this->formData);
        $count = Role::get()->count();
        $role = Role::orderBy('id', 'asc')->first();
        $this->repository->destroy($role);

        $this->assertEquals($count - 1, Role::get()->count());
    }

    /**
     * @test
     * get form data successfully
     * permissions of role
     *
     * @return void
     */
    public function if_user_can_render_specific_role_permissions_successfully()
    {
        $this->repository->store($this->formData);
        $role = Role::orderBy('id', 'asc')->first();
        $response = $this->repository->getPermissions(['id' => $role->id]);

        $this->assertEquals($role->getAllPermissions()->count(), $response->count());
    }
}
