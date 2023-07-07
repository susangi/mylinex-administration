<?php

namespace Tests\Unit;

use Administration\Repositories\UserRepository;
use Administration\Traits\AuthenticationHelperTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use AuthenticationHelperTrait,
        RefreshDatabase;

    private $user = [];
    private $formData = [];
    private $permission;
    private $newRole;
    private UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createSuperUser();
        $this->be($this->user);
        $this->formData = [

        ];
        $this->repository = new UserRepository();
    }

    /**
     * @test
     * user can load data for page
     * roles,
     * landing pages
     *
     * @return void
     */
    public function if_user_can_load_page_data_correctly()
    {
        $response = $this->repository->loadPageData();

        $this->assertArrayHasKey('roles', $response);
        $this->assertArrayHasKey('landing_page', $response);
    }
}
