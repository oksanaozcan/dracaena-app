<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestHelper;
use Database\Seeders\RoleSeeder;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->user = $this->createUserWithRole("assistant");
    }

    public function test_1_it_displays_the_user_index_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessPage("admin", "users.index", "user", "index");
    }

    public function test_2_it_does_not_display_the_user_index_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanNotAccessPage("manager", "users.index","index");
    }

    public function test_3_it_does_not_display_the_user_index_page_for_authenticated_assistant_users()
    {
        $this->assertRoleCanNotAccessPage("assistant", "users.index","index");
    }

    public function test_4_it_displays_the_create_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessPage("admin", "users.create", "user", "create");
    }

    public function test_5_it_does_not_display_the_user_create_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanNotAccessPage("manager", "users.create","create");
    }

    public function test_6_it_does_not_display_the_user_create_page_for_authenticated_assistant_users()
    {
        $this->assertRoleCanNotAccessPage("assistant", "users.create","create");
    }

    public function test_7_it_does_not_allow_authorized_manager_users_to_edit_an_other_user()
    {
        $this->assertRoleCanNotEditModel("manager", $this->user, "users.edit");
    }

    public function test_8_it_does_not_allow_authorized_assistant_users_to_edit_an_other_user()
    {
        $this->assertRoleCanNotEditModel("assistant", $this->user, "users.edit");
    }

    public function test_9_it_redirects_not_authenticated_users_from_index_to_login_page()
    {
        $this->assertRedirectNotAuthUsersToLogin("users.index");
    }

    public function test_10_it_redirects_not_authenticated_users_from_create_to_login_page()
    {
       $this->assertRedirectNotAuthUsersToLogin("users.create");
    }

    public function test_11_it_allows_authorized_admin_users_to_edit_an_other_user()
    {
        $this->assertRoleCanEditModel("admin", $this->user, "users.edit", "user.edit");
    }

    public function test_12_it_redirects_not_authenticated_users_from_edit_to_login_page()
    {
        $this->assertRedirectNotAuthUsersToLogin("users.edit", $this->user);
    }

    public function test_13_it_displays_the_show_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessShowPage("admin", $this->user, "users.show", "user.show");
    }

    public function test_14_it_does_not_display_the_show_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanNotAccessShowPage("manager", $this->user, "users.show", "user.show");
    }

    public function test_15_it_does_not_display_the_show_page_for_authenticated_assistant_users()
    {
        $this->assertRoleCanNotAccessShowPage("assistant", $this->user, "users.show", "user.show");
    }

    public function test_16_it_redirects_not_authenticated_users_from_show_to_login_page()
    {
        $this->assertRedirectNotAuthUsersToLogin("users.show", $this->user);
    }

    public function test_17_it_allows_authorized_admin_users_to_delete_an_other_user()
    {
        $this->assertRoleCanDeleteModel("admin", $this->user, "users.destroy", "users");
    }

    public function test_18_it_does_not_allow_authorized_manager_users_to_delete_an_other_user()
    {
        $this->assertRoleCanNotDeleteModel("manager", $this->user, "users.destroy");
    }

    public function test_19_it_does_not_allow_authorized_assistant_users_to_delete_an_other_user()
    {
        $this->assertRoleCanNotDeleteModel("assistant", $this->user, "users.destroy");
    }
}
