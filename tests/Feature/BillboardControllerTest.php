<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Tests\TestHelper;
use App\Models\Billboard;
use App\Types\RoleType;
use Illuminate\Support\Str;
use Database\Seeders\RoleSeeder;

class BillboardControllerTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
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
        $response = $this->get(route('billboards.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('verification.notice'));
    }

    public function test_8_it_redirects_not_authenticated_users_from_create_to_login_page()
    {
        $response = $this->get(route('billboards.create'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    public function test_9_it_allows_authorized_admin_users_to_edit_a_billboard()
    {
        $billboard = $this->createBillboard();
        $this->assertRoleCanEditModel("admin", $billboard, "billboards.edit", "billboard.edit");
    }

    public function test_10_it_allows_authorized_manager_users_to_edit_a_billboard()
    {
        $billboard = $this->createBillboard();
        $this->assertRoleCanEditModel("manager", $billboard, "billboards.edit", "billboard.edit");
    }

    public function test_11_it_does_not_allow_authorized_assistant_users_to_edit_a_billboard()
    {
        $billboard = $this->createBillboard();
        $this->assertRoleCanNotEditModel("assistant", $billboard, "billboards.edit");
    }

    public function test_12_it_redirects_not_authenticated_users_from_edit_to_login_page()
    {
        $billboard = $this->createBillboard();
        $response = $this->get(route('billboards.edit', $billboard->id));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    public function test_13_it_displays_the_show_page_for_authenticated_admin_users()
    {
        $billboard = $this->createBillboard();
        $this->assertRoleCanAccessShowPage("admin", $billboard, "billboards.show", "billboard.show");
    }

    public function test_14_it_displays_the_show_page_for_authenticated_manager_users()
    {
        $billboard = $this->createBillboard();
        $this->assertRoleCanAccessShowPage("manager", $billboard, "billboards.show", "billboard.show");
    }

    public function test_15_it_displays_the_show_page_for_authenticated_assistant_users()
    {
        $billboard = $this->createBillboard();
        $this->assertRoleCanAccessShowPage("assistant", $billboard, "billboards.show", "billboard.show");
    }

    public function test_16_it_redirects_not_authenticated_users_from_show_to_login_page()
    {
        $billboard = $this->createBillboard();
        $response = $this->get(route('billboards.show', $billboard->id));

        $response->assertStatus(302)
            ->assertRedirect(route('verification.notice'));
    }

    public function test_17_it_allows_authorized_admin_users_to_delete_a_billboard()
    {
        $billboard = $this->createBillboard();
        $this->assertRoleCanDeleteModel("admin", $billboard, "billboards.destroy", "billboards");
    }

    public function test_18_it_allows_authorized_manager_users_to_delete_a_billboard()
    {
        $billboard = $this->createBillboard();
        $this->assertRoleCanDeleteModel("manager", $billboard, "billboards.destroy", "billboards");
    }

    public function test_19_it_does_not_allow_authorized_assistant_users_to_delete_a_billboard()
    {
        $billboard = $this->createBillboard();
        $user = User::factory()->assistant()->create();
        $this->assertTrue($user->hasRole(RoleType::ASSISTANT));

        $response = $this->actingAs($user)
            ->delete(route('billboards.destroy', $billboard));

        $response->assertStatus(403);
    }
}
