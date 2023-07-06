<?php

namespace Tests\Unit;

use Administration\Models\User;
use Administration\Services\PasswordPolicyService;
use Administration\Exceptions\PasswordValidationException;
use Administration\Traits\AuthenticationHelperTrait;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Administration\Traits\PasswordPolicyMessageTrait;

class UserTest extends TestCase
{
    use AuthenticationHelperTrait,
        RefreshDatabase,
        PasswordPolicyMessageTrait;

    private $user = [];
    private $formData = [];
    private PasswordPolicyService $passwordPolicyService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createSuperUser();
        $this->formData = [
            'email' => 'test@mylinex.com',
            'name' => 'test@mylinex.com',
            'password' => 'Mylinex@1234',
            'landing_page' => 'home',
        ];
        $this->passwordPolicyService = new PasswordPolicyService($this->user);
    }

    /**
     * @test
     * user can create user
     * with not exist user
     *
     * @return void
     */
    public function if_user_can_store_user()
    {
        User::createUser($this->formData, 1);

        $this->assertDatabaseHas(
            'users',
            [
                'email' => $this->formData['email'],
                'name' => $this->formData['name'],
                'landing_page' => $this->formData['landing_page']
            ]
        );
    }

    /**
     * @test
     * user can not store with missing required fields
     * name, email, password, created_by
     *
     * @return void
     */
    public function if_user_can_not_store_user_with_missing_required_fields()
    {
        $this->expectException(QueryException::class);

        User::create(['name' => null, 'email' => null, 'password' => null, 'created_by' => null]);
    }



    /**
     * @test
     * check is update user details when unlock user
     * reset login attempts count as 0
     * update last_login date as current
     *
     * @return void
     */
    public function if_user_can_update_user_details_if_unlock_user()
    {
        //update user login attempts
        $this->user->update(['login_attempts' => 7]);
        User::unlockUserAccount($this->user);
        $current_time = Carbon::now()->toDateTimeString();

        $this->assertDatabaseHas('users', ['id' => $this->user->id, 'login_attempts' => 0, 'last_login' => $current_time]);
    }

    /**
     * @test
     * Only able to change password if that user password not change between last 24 hours
     */
    public function if_user_can_not_reset_password_more_than_once_in_last_24_hours()
    {
        $this->expectException(PasswordValidationException::class);
        $this->isPasswordChangedLast24Hours($this->user);
    }

    /**
     * @test
     * check is update user details when reset password
     * update last login as current
     * update password changed at date as current
     *
     * @return void
     */
    public function if_user_can_update_user_details_if_reset_password()
    {
        User::where('id', $this->user->id)->update(['password_changed_at' => '2021-01-01 07:12:15', 'last_login' => '2021-01-01 07:12:15']);
        $this->user = User::where('id', $this->user->id)->first();
        User::resetUserPassword($this->user, 'Mylinex@1234');
        $current_time = Carbon::now()->toDateTimeString();
        $this->assertDatabaseHas('users', ['password_changed_at' => $current_time, 'last_login' => $current_time]);
    }

}
