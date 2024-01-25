<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestHelper;
use Database\Seeders\RoleSeeder;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_1_it_displays_the_tag_index_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessPage("admin", "tags.index", "tag", "index");
    }

    public function test_2_it_displays_the_tag_index_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanAccessPage("manager", "tags.index", "tag", "index");
    }

    public function test_3_it_displays_the_tag_index_page_for_authenticated_assistant_users()
    {
        $this->assertRoleCanAccessPage("assistant", "tags.index", "tag", "index");
    }

    public function test_4_it_displays_the_create_page_for_authenticated_admin_users()
    {
        $this->assertRoleCanAccessPage("admin", "tags.create", "tag", "create");
    }

    public function test_5_it_displays_the_create_page_for_authenticated_manager_users()
    {
        $this->assertRoleCanAccessPage("manager", "tags.create", "tag", "create");
    }

    public function test_6_it_displays_the_create_page_for_authenticated_assistant_users()
    {
        $this->assertRoleCanAccessPage("assistant", "tags.create", "tag", "create");
    }

    public function test_7_it_does_not_allow_authorized_assistant_users_to_edit_a_tag()
    {
        $t = $this->createTag();
        $this->assertRoleCanNotEditModel("assistant", $t, "tags.edit");
    }

    public function test_8_it_redirects_not_authenticated_users_from_index_to_login_page()
    {
        $this->assertRedirectNotAuthUsersFromPageToVerifNoticeRoute("tags.index");
    }

    public function test_9_it_redirects_not_authenticated_users_from_create_to_login_page()
    {
       $this->assertRedirectNotAuthUsersToLogin("tags.create");
    }

    public function test_10_it_allows_authorized_admin_users_to_edit_a_tag()
    {
        $t = $this->createTag();
        $this->assertRoleCanEditModel("admin", $t, "tags.edit", "tag.edit");
    }

    public function test_11_it_allows_authorized_manager_users_to_edit_a_tag()
    {
        $t = $this->createTag();
        $this->assertRoleCanEditModel("manager", $t, "tags.edit", "tag.edit");
    }

    public function test_12_it_redirects_not_authenticated_users_from_edit_to_login_page()
    {
        $t = $this->createTag();
        $this->assertRedirectNotAuthUsersToLogin("tags.edit", $t);
    }

    public function test_13_it_displays_the_show_page_for_authenticated_admin_users()
    {
        $t = $this->createTag();
        $this->assertRoleCanAccessShowPage("admin", $t, "tags.show", "tag.show");
    }

    public function test_14_it_displays_the_show_page_for_authenticated_manager_users()
    {
        $t = $this->createTag();
        $this->assertRoleCanAccessShowPage("manager", $t, "tags.show", "tag.show");
    }

    public function test_15_it_displays_the_show_page_for_authenticated_assistant_users()
    {
        $t = $this->createTag();
        $this->assertRoleCanAccessShowPage("assistant", $t, "tags.show", "tag.show");
    }

    public function test_16_it_redirects_not_authenticated_users_from_show_to_login_page()
    {
        $t = $this->createTag();
        $this->assertRedirectNotAuthUsersFromPageToVerifNoticeRoute("tags.show", $t);
    }

    public function test_17_it_allows_authorized_admin_users_to_delete_a_tag()
    {
        $t = $this->createTag();
        $this->assertRoleCanDeleteModel("admin", $t, "tags.destroy", "tags");
    }

    public function test_18_it_allows_authorized_manager_users_to_delete_a_tag()
    {
        $t = $this->createTag();
        $this->assertRoleCanDeleteModel("manager", $t, "tags.destroy", "tags");
    }

    public function test_19_it_does_not_allow_authorized_assistant_users_to_delete_a_tag()
    {
        $t = $this->createTag();
        $this->assertRoleCanNotDeleteModel("assistant", $t, "tags.destroy");
    }
}
