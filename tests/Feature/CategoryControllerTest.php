<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestHelper;
use Database\Seeders\RoleSeeder;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_1_it_displays_the_category_index_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessPage("admin", "categories.index", "category", "index");
    }

    public function test_2_it_displays_the_category_index_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanAccessPage("manager", "categories.index", "category", "index");
    }

    public function test_3_it_displays_the_category_index_page_for_authenticated_assistant_users()
    {
        $this->assertRoleCanAccessPage("assistant", "categories.index", "category", "index");
    }

    public function test_4_it_displays_the_create_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessPage("admin", "categories.create", "category", "create");
    }

    public function test_5_it_displays_the_create_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanAccessPage("manager", "categories.create", "category", "create");
    }

    public function test_6_it_does_not_allow_authorized_assistant_users_to_edit_a_category()
    {
        $cat = $this->createCategory();
        $this->assertRoleCanNotEditModel("assistant", $cat, "categories.edit");
    }

    public function test_7_it_redirects_not_authenticated_users_from_index_to_login_page()
    {
        $this->assertRedirectNotAuthUsersFromPageToVerifNoticeRoute("categories.index");
    }

    public function test_8_it_redirects_not_authenticated_users_from_create_to_login_page()
    {
       $this->assertRedirectNotAuthUsersToLogin("categories.create");
    }

    public function test_9_it_allows_authorized_admin_users_to_edit_a_category()
    {
        $cat = $this->createCategory();
        $this->assertRoleCanEditModel("admin", $cat, "categories.edit", "category.edit");
    }

    public function test_10_it_allows_authorized_manager_users_to_edit_a_category()
    {
        $cat = $this->createCategory();
        $this->assertRoleCanEditModel("manager", $cat, "categories.edit", "category.edit");
    }

    public function test_11_it_redirects_not_authenticated_users_from_edit_to_login_page()
    {
        $cat = $this->createCategory();
        $this->assertRedirectNotAuthUsersToLogin("categories.edit", $cat);
    }

    public function test_12_it_displays_the_show_page_for_authenticated_admin_users()
    {
        $cat = $this->createCategory();
        $this->assertRoleCanAccessShowPage("admin", $cat, "categories.show", "category.show");
    }

    public function test_13_it_displays_the_show_page_for_authenticated_manager_users()
    {
        $cat = $this->createCategory();
        $this->assertRoleCanAccessShowPage("manager", $cat, "categories.show", "category.show");
    }

    public function test_14_it_displays_the_show_page_for_authenticated_assistant_users()
    {
        $cat = $this->createCategory();
        $this->assertRoleCanAccessShowPage("assistant", $cat, "categories.show", "category.show");
    }

    public function test_15_it_redirects_not_authenticated_users_from_show_to_login_page()
    {
        $cat = $this->createCategory();
        $this->assertRedirectNotAuthUsersFromPageToVerifNoticeRoute("categories.show", $cat);
    }

    public function test_16_it_allows_authorized_admin_users_to_delete_a_category()
    {
        $cat = $this->createCategory();
        $this->assertRoleCanDeleteModel("admin", $cat, "categories.destroy", "categories");
    }

    public function test_17_it_allows_authorized_manager_users_to_delete_a_category()
    {
        $cat = $this->createCategory();
        $this->assertRoleCanDeleteModel("admin", $cat, "categories.destroy", "categories");
    }

    public function test_18_it_does_not_allow_authorized_assistant_users_to_delete_a_category()
    {
        $cat = $this->createCategory();
        $this->assertRoleCanNotDeleteModel("assistant", $cat, "categories.destroy");
    }
}
