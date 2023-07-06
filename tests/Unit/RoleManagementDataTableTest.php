<?php


use Administration\Repositories\RoleRepository;
use Administration\Traits\AuthenticationHelperTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Administration\Models\Role;
use Tests\TestCase;
use Administration\Traits\DataTableSampleDataTrait;

class RoleManagementDataTableTest extends TestCase
{
    use AuthenticationHelperTrait,
        DataTableSampleDataTrait,
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
        Permission::firstOrCreate(['name' => 'roles edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'roles delete', 'guard_name' => 'web']);
        Role::factory()->count(10)->create();
        $_REQUEST['draw'] = 1;
        $this->repository = new RoleRepository();
    }

    /**
     * @test
     * check data table data load
     *
     * @return void
     */
    public function if_user_can_view_table_data()
    {
        $tableData = $this->repository->tableData($this->dataTableDataSet());

        $this->assertEquals(10, sizeof($tableData['data']));
    }

    /**
     * @test
     * check data table sort
     *
     * @return void
     */
    public function if_user_can_sort_table_data_by_id()
    {
        $tableData = $this->repository->tableData($this->dataTableDataSet(0, 'desc'));
        $lastId = Role::orderBy('id', 'desc')->first();

        $this->assertEquals($lastId->name, $tableData['data'][0][0]);
    }

    /**
     * @test
     * check data table search
     *
     * @return void
     */
    public function if_user_can_search_a_value_in_table_data()
    {
        $search = Role::orderBy('id', 'asc')->first();

        $tableData = $this->repository->tableData($this->dataTableDataSet(0, 'desc', 0, 10, $search->name));

        $this->assertEquals(
            Role::where('name', $search->name)->get()->count(),
            sizeof($tableData['data'])
        );
    }

    /**
     * @test
     * check load data count and total count equal
     *
     * @return void
     */
    public function if_user_can_view_data_count_and_total_data_count_equal()
    {
        $tableData = $this->repository->tableData($this->dataTableDataSet());
        $totalCount = Role::all()->count();

        $this->assertEquals($totalCount, $tableData['recordsTotal']);
    }

    /**
     * @test
     * check filter data count and filter count equal
     *
     * @return void
     */
    public function if_user_can_view_filter_data_count_and_filtered_count_equal()
    {
        $search = Role::orderBy('id', 'asc')->first();

        $tableData = $this->repository->tableData($this->dataTableDataSet(0, 'desc', 0, 10, $search->name));

        $this->assertEquals(Role::where('name', $search->name)->get()->count(), $tableData['recordsFiltered']);
    }
}
