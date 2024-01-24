<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestHelper;
use Database\Seeders\RoleSeeder;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_1_it_displays_the_product_index_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessPage("admin", "products.index", "product", "index");
    }

    public function test_2_it_displays_the_product_index_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanAccessPage("manager", "products.index", "product", "index");
    }

    public function test_3_it_displays_the_product_index_page_for_authenticated_assistant_users()
    {
        $this->assertRoleCanAccessPage("assistant", "products.index", "product", "index");
    }

    public function test_4_it_displays_the_create_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessPage("admin", "products.create", "product", "create");
    }

    public function test_5_it_displays_the_create_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanAccessPage("manager", "products.create", "product", "create");
    }

    public function test_6_it_displays_the_create_page_for_authenticated_assistant_users()
    {
        $this->assertRoleCanAccessPage("assistant", "products.create", "product", "create");
    }

    public function test_7_it_does_not_allow_authorized_assistant_users_to_edit_a_product()
    {
        $data = $this->createCategoryAndProduct();
        $p = $data["product"];
        $this->assertRoleCanNotEditModel("assistant", $p, "products.edit");
    }

    public function test_8_it_redirects_not_authenticated_users_from_index_to_login_page()
    {
        $this->assertRedirectNotAuthUsersToLogin("products.index");
    }

    public function test_9_it_redirects_not_authenticated_users_from_create_to_login_page()
    {
       $this->assertRedirectNotAuthUsersToLogin("products.create");
    }

    public function test_10_it_allows_authorized_admin_users_to_edit_a_product()
    {
        $data = $this->createCategoryAndProduct();
        $p = $data["product"];
        $this->assertRoleCanEditModel("admin", $p, "products.edit", "product.edit");
    }

    public function test_11_it_allows_authorized_manager_users_to_edit_a_product()
    {
        $data = $this->createCategoryAndProduct();
        $p = $data["product"];
        $this->assertRoleCanEditModel("manager", $p, "products.edit", "product.edit");
    }

    public function test_12_it_redirects_not_authenticated_users_from_edit_to_login_page()
    {
        $data = $this->createCategoryAndProduct();
        $p = $data["product"];
        $this->assertRedirectNotAuthUsersToLogin("categories.edit", $p);
    }

    public function test_13_it_displays_the_show_page_for_authenticated_admin_users()
    {
        $data = $this->createCategoryAndProduct();
        $p = $data["product"];
        $this->assertRoleCanAccessShowPage("admin", $p, "products.show", "product.show");
    }

    public function test_14_it_displays_the_show_page_for_authenticated_manager_users()
    {
        $data = $this->createCategoryAndProduct();
        $p = $data["product"];
        $this->assertRoleCanAccessShowPage("manager", $p, "products.show", "product.show");
    }

    public function test_15_it_displays_the_show_page_for_authenticated_assistant_users()
    {
        $data = $this->createCategoryAndProduct();
        $p = $data["product"];
        $this->assertRoleCanAccessShowPage("assistant", $p, "products.show", "product.show");
    }

    public function test_16_it_redirects_not_authenticated_users_from_show_to_login_page()
    {
        $data = $this->createCategoryAndProduct();
        $p = $data["product"];
        $this->assertRedirectNotAuthUsersToLogin("products.show", $p);
    }

    public function test_17_it_allows_authorized_admin_users_to_delete_a_product()
    {
        $data = $this->createCategoryAndProduct();
        $p = $data["product"];
        $this->assertRoleCanDeleteModel("admin", $p, "products.destroy", "products");
    }

    public function test_18_it_allows_authorized_manager_users_to_delete_a_product()
    {
        $data = $this->createCategoryAndProduct();
        $p = $data["product"];
        $this->assertRoleCanDeleteModel("manager", $p, "products.destroy", "products");
    }

    public function test_19_it_does_not_allow_authorized_assistant_users_to_delete_a_product()
    {
        $data = $this->createCategoryAndProduct();
        $p = $data["product"];
        $this->assertRoleCanNotDeleteModel("assistant", $p, "products.destroy");
    }
}
