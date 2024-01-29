<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestHelper;
use Database\Seeders\RoleSeeder;

class CategoryFilterControllerTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->cf = $this->createCategoryFilter();
    }

    public function test_1_it_displays_the_index_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessPage("admin", "category-filters.index", "category-filter", "index");
    }

    public function test_2_it_displays_the_index_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanAccessPage("manager", "category-filters.index", "category-filter", "index");
    }

    public function test_3_it_displays_the_index_page_for_authenticated_assistant_users()
    {
        $this->assertRoleCanAccessPage("assistant", "category-filters.index", "category-filter", "index");
    }

    public function test_4_it_displays_the_create_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessPage("admin", "category-filters.create", "category-filter", "create");
    }

    public function test_5_it_displays_the_create_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanAccessPage("manager", "category-filters.create", "category-filter", "create");
    }

    public function test_6_it_redirects_not_authenticated_users_from_index_to_login_page()
    {
        $this->assertRedirectNotAuthUsersFromPageToVerifNoticeRoute("category-filters.index");
    }

    public function test_7_it_redirects_not_authenticated_users_from_create_to_login_page()
    {
       $this->assertRedirectNotAuthUsersToLogin("category-filters.create");
    }

    public function test_8_it_allows_authorized_admin_users_to_edit_a_category_filter()
    {
        $this->assertRoleCanEditModel("admin", $this->cf, "category-filters.edit", "category-filter.edit");
    }

    public function test_9_it_allows_authorized_manager_users_to_edit_a_category_filter()
    {
        $this->assertRoleCanEditModel("manager", $this->cf, "category-filters.edit", "category-filter.edit");
    }

    public function test_10_it_does_not_allow_authorized_assistant_users_to_edit_a_category_filter()
    {
        $this->assertRoleCanNotEditModel("assistant", $this->cf, "category-filters.edit");
    }

    public function test_11_it_redirects_not_authenticated_users_from_edit_to_login_page()
    {
        $this->assertRedirectNotAuthUsersToLogin("category-filters.edit", $this->cf);
    }

    public function test_12_it_displays_the_show_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessShowPage("admin", $this->cf, "category-filters.show", "category-filter.show");
    }

    public function test_13_it_displays_the_show_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanAccessShowPage("manager", $this->cf, "category-filters.show", "category-filter.show");
    }

    public function test_14_it_displays_the_show_page_for_authenticated_assistant_users()
    {
        $this->assertRoleCanAccessShowPage("assistant", $this->cf, "category-filters.show", "category-filter.show");
    }

    public function test_15_it_redirects_not_authenticated_users_from_show_to_login_page()
    {
        $this->assertRedirectNotAuthUsersFromPageToVerifNoticeRoute("category-filters.show", $this->cf);
    }

    public function test_16_it_allows_authorized_admin_users_to_delete_a_category_filter()
    {
        $this->assertRoleCanDeleteModel("admin", $this->cf, "category-filters.destroy", "category-filters", "category_filters");
    }

    public function test_17_it_allows_authorized_manager_users_to_delete_a_category_filter()
    {
        $this->assertRoleCanDeleteModel("manager", $this->cf, "category-filters.destroy", "category-filters", "category_filters");
    }

    public function test_18_it_does_not_allow_authorized_assistant_users_to_delete_a_category_filter()
    {
        $this->assertRoleCanNotDeleteModel("assistant", $this->cf, "category-filters.destroy");
    }
}
