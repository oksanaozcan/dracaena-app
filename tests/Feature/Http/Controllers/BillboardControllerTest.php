<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestHelper;
use Database\Seeders\RoleSeeder;

class BillboardControllerTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->b = $this->createBillboard();
    }

    public function test_1_it_displays_the_billboard_index_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessPage("admin", "billboards.index", "billboard", "index");
    }

    public function test_2_it_displays_the_billboard_index_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanAccessPage("manager", "billboards.index", "billboard", "index");
    }

    public function test_3_it_displays_the_billboard_index_page_for_authenticated_assistant_users()
    {
        $this->assertRoleCanAccessPage("assistant", "billboards.index", "billboard", "index");
    }

    public function test_4_it_displays_the_create_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessPage("admin", "billboards.create", "billboard", "create");
    }

    public function test_5_it_displays_the_create_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanAccessPage("manager", "billboards.create", "billboard", "create");
    }

    public function test_6_it_displays_the_create_page_for_authenticated_assistant_users()
    {
        $this->assertRoleCanAccessPage("assistant", "billboards.create", "billboard", "create");
    }

    public function test_7_it_redirects_not_authenticated_users_from_index_to_login_page()
    {
        $this->assertRedirectNotAuthUsersFromPageToVerifNoticeRoute("billboards.index");
    }

    public function test_8_it_redirects_not_authenticated_users_from_create_to_login_page()
    {
        $this->assertRedirectNotAuthUsersToLogin("billboards.create");
    }

    public function test_9_it_allows_authorized_admin_users_to_edit_a_billboard()
    {
        $this->assertRoleCanEditModel("admin", $this->b, "billboards.edit", "billboard.edit");
    }

    public function test_10_it_allows_authorized_manager_users_to_edit_a_billboard()
    {
        $this->assertRoleCanEditModel("manager", $this->b, "billboards.edit", "billboard.edit");
    }

    public function test_11_it_does_not_allow_authorized_assistant_users_to_edit_a_billboard()
    {
        $this->assertRoleCanNotEditModel("assistant", $this->b, "billboards.edit");
    }

    public function test_12_it_redirects_not_authenticated_users_from_edit_to_login_page()
    {
        $this->assertRedirectNotAuthUsersToLogin("billboards.create", $this->b);
    }

    public function test_13_it_displays_the_show_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessShowPage("admin", $this->b, "billboards.show", "billboard.show");
    }

    public function test_14_it_displays_the_show_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanAccessShowPage("manager", $this->b, "billboards.show", "billboard.show");
    }

    public function test_15_it_displays_the_show_page_for_authenticated_assistant_users()
    {
        $this->assertRoleCanAccessShowPage("assistant", $this->b, "billboards.show", "billboard.show");
    }

    public function test_16_it_redirects_not_authenticated_users_from_show_to_login_page()
    {
        $this->assertRedirectNotAuthUsersFromPageToVerifNoticeRoute("billboards.show", $this->b);
    }

    public function test_17_it_allows_authorized_admin_users_to_delete_a_billboard()
    {
        $this->assertRoleCanDeleteModel("admin", $this->b, "billboards.destroy", "billboards");
    }

    public function test_18_it_allows_authorized_manager_users_to_delete_a_billboard()
    {
        $this->assertRoleCanDeleteModel("manager", $this->b, "billboards.destroy", "billboards");
    }

    public function test_19_it_does_not_allow_authorized_assistant_users_to_delete_a_billboard()
    {
        $this->assertRoleCanNotDeleteModel("assistant", $this->b, "billboards.destroy");
    }
}
