<?php

namespace Tests\Feature;

use Administration\Models\Permission;
use Administration\Models\Role;
use Administration\Repositories\RoleRepository;
use Administration\Traits\AuthenticationHelperTrait;
use Administration\Traits\DataTableSampleDataTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use AuthenticationHelperTrait,
        DataTableSampleDataTrait,
        RefreshDatabase;

    private $user = [];
    private $formData = [];
    private RoleRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createSuperUser();
        $this->formData = [
            'name' => 'Admin',
            'permissions' => []
        ];
        $this->repository = new RoleRepository();
        Permission::firstOrCreate(['name' => 'roles edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'roles delete', 'guard_name' => 'web']);
        Role::factory()->count(10)->create();
        $_REQUEST['draw'] = 1;
    }

    /**
     * @test
     * If user can be view role page
     *
     * @return void
     */
    public function if_user_can_be_see_role_page()
    {
        $response = $this->be($this->user)->get('/roles');
        $response->assertSee('Roles')->assertStatus(200);
    }

    /**
     * @test
     * If unauthenticated user can be view role page
     *
     * @return void
     */
    public function if_unauthenticated_user_can_be_see_role_page()
    {
        $response = $this->get('/roles');

        $response->assertRedirect('/login')->assertStatus(302);
    }

    /**
     * @test
     * authenticated user can store role successfully.
     *
     * @return void
     */
    public function if_authenticated_user_can_store_a_role()
    {
        $response = $this->be($this->user)->post('/roles', $this->formData);

        $response->assertStatus(200);
        $response->assertSee('Role created successfully');
    }

    /**
     * @test
     * unauthenticated user can not store role.
     *
     * @return void
     */
    public function if_unauthenticated_user_can_store_a_role()
    {
        $response = $this->post('/roles', $this->formData);

        $response->assertStatus(500);
    }

    /**
     * @test
     * can not create role
     * required role name
     *
     * @return void
     */
    public function if_user_can_not_store_a_role_whether_required_role_name()
    {
        $response = $this->be($this->user)->post('/roles', array_merge($this->formData, ['name' => null]));

        $response->assertSessionHasErrors('name');
    }

    /**
     * @test
     * can not create role
     * unique role name
     *
     * @return void
     */
    public function if_user_can_not_store_a_role_whether_unique_role_name()
    {
        $this->repository->store($this->formData);
        $response = $this->be($this->user)->post('/roles', $this->formData);

        $response->assertSessionHasErrors('name');
    }

    /**
     * @test
     * authenticated user can update role successfully.
     *
     * @return void
     */
    public function if_authenticated_user_can_update_a_role()
    {
        $role = $this->repository->store($this->formData);
        $response = $this->be($this->user)->put
        ("/roles/{$role->id}",
            array_merge($this->formData, ['name' => 'Test'])
        );

        $response->assertStatus(200);
        $response->assertSeeText('Role updated successfully');
    }

    /**
     * @test
     * unauthenticated user can not update role.
     *
     * @return void
     */
    public function if_unauthenticated_user_can_update_a_role()
    {
        $role = $this->repository->store($this->formData);
        $response = $this->put
        ("/roles/{$role->id}",
            array_merge($this->formData, ['name' => 'Test'])
        );

        $response->assertStatus(500);
    }

    /**
     * @test
     * can not update role
     * required role name
     *
     * @return void
     */
    public function if_user_can_not_update_a_role_whether_required_role_name()
    {
        $role = $this->repository->store($this->formData);
        $response = $this->be($this->user)->put
        ("/roles/{$role->id}",
            array_merge($this->formData, ['name' => null])
        );

        $response->assertSessionHasErrors('name');
    }

    /**
     * @test
     * can not update role
     * unique role name
     *
     * @return void
     */
    public function if_user_can_not_update_a_role_whether_unique_role_name()
    {
        $this->repository->store($this->formData);
        $role = $this->repository->store
        (
            array_merge($this->formData, ['name' => 'Test'])
        );
        $response = $this->be($this->user)->put
        (
            "/roles/{$role->id}",
            $this->formData
        );

        $response->assertSessionHasErrors('name');
    }

    /**
     * @test
     * authenticated use can delete role successfully
     *
     * @return void
     */
    public function if_authenticated_user_can_delete_a_role_successfully()
    {
        $role = $this->repository->store($this->formData);;
        $response = $this->be($this->user)->delete("/roles/$role->id}");

        $response->assertStatus(200);
        $response->assertSeeText('Role deleted successfully');
    }

    /**
     * @test
     * unauthenticated user can not delete role
     *
     * @return void
     */
    public function if_unauthenticated_user_can_delete_a_role_successfully()
    {
        $role = $this->repository->store($this->formData);;
        $response = $this->delete("/roles/$role->id}");

        $response->assertStatus(500);
    }

    /**
     * @test
     * authenticated user can view roles datatable
     *
     * @return void
     */
    public function if_authenticated_user_can_load_roles_datatable()
    {
        $response = $this->be($this->user)->get("/roles/table/data?{$this->dataTableRequestDataSet()}");

        $response->assertStatus(200);

        $response->assertJsonStructure(
            [
                'data'
            ]
        );

        $response->assertJsonCount(10, 'data');
    }

    /**
     * @test
     * unauthenticated user can not view roles datatable
     *
     * @return void
     */
    public function if_unauthenticated_user_can_load_roles_datatable()
    {
        $response = $this->get("/roles/table/data?{$this->dataTableRequestDataSet()}");

        $response->assertStatus(500);
    }

}
