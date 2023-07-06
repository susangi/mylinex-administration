<?php

namespace Tests\Unit;

use Administration\Models\Menu;
use Administration\Models\Permission;
use Administration\Traits\AuthenticationHelperTrait;
use Administration\Traits\PasswordPolicyMessageTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Tests\TestCase;
use Illuminate\Http\Request;

class MenuManagementTest extends TestCase
{
    use AuthenticationHelperTrait,
        RefreshDatabase,
        PasswordPolicyMessageTrait;

    private $user = [];
    private $formData = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createSuperUser();
        $this->formData = [
            'isParent' => 'on',
            'parent_id'=>1,
            'title'=> 'test title',
            'url' => 'doc.index',
            'permissions' => ['index','create'],
            'permission_tags'=>['report,blade']
        ];
    }

    /**
    * @test
    * menu is not parent, when given url not exists in route list throw an error
    *
    * @return void
    */
    public function throw_error_if_url_not_exist_in_route_list()
    {
        $this->expectException(RouteNotFoundException::class);
        $this->formData = array_merge($this->formData, ['url' => 'test_url']);
        Menu::routeHas($this->formData['url']);
    }
}
