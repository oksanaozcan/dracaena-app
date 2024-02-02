<?php

namespace Tests\Feature\Http\Livewire\Billboard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;
use Database\Seeders\RoleSeeder;
use Tests\TestHelper;
use App\Http\Livewire\Billboard\CreateForm;
use App\Http\Livewire\Billboard\Table;
use App\Models\Billboard;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BillboardTableTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->category = $this->createCategory();
        $this->b = $this->createBillboard();
        $this->admin = $this->createUserWithRole("admin");
        Livewire::actingAs($this->admin);
    }

    public function test_1_it_can_render_the_component()
    {
        $this->get('/billboards')->assertSeeLivewire(Table::class);
    }

    public function test_2_it_can_render_the_create_form_component()
    {
        $this->get('/billboards')->assertSeeLivewire(CreateForm::class);
    }

    public function test_3_it_can_render_with_billboards()
    {
        Livewire::test(Table::class)
            ->assertSee($this->b->description);
    }

    public function test_4_it_can_sort_billboards()
    {
        $billboard1 = Billboard::factory()->create(['description' => 'zdk']);
        $billboard2 = Billboard::factory()->create(['description' => 'abc']);

        Livewire::test(Table::class)
            ->call('sortBy', 'description')
            ->assertSeeInOrder([$billboard2->description, $billboard1->description]);
    }

    public function test_5_it_can_redirect_to_edit_page_of_model()
    {
        Livewire::test(Table::class, ['id' => $this->b->id])
        ->assertSee($this->b->id)
        ->call("editBillboard", $this->b->id)
        ->assertRedirect(route("billboards.edit", $this->b->id));
    }

    public function test_6_admin_can_destroy_billboard_usind_table_component()
    {
        $this->canDestroyBillboard();
    }

    public function test_7_manager_can_destroy_billboard_usind_table_component()
    {
        $manager = $this->createUserWithRole("manager");
        Livewire::actingAs($manager);
        $this->canDestroyBillboard();
    }

    public function test_8_assistant_can_not_destroy_billboard_usind_table_component()
    {
        $assistant = $this->createUserWithRole("assistant");
        Livewire::actingAs($assistant);
        $this->canDestroyBillboard(false);
    }

    public function test_9_it_emits_event_billboard_added_and_calls_render_method()
    {
        Livewire::test(CreateForm::class)
            ->set('description', 'string for testing render method of Table component')
            ->set('image', UploadedFile::fake()->image('test_image.jpg'))
            ->set('category_id',  $this->category->id)
            ->call('submitForm')
            ->assertEmitted('billboardAdded');

        Livewire::test(Table::class)
            ->assertSee('string for testing render method of Table component');
    }

    protected function canDestroyBillboard(bool $case = true)
    {
        $testId = $this->b->id;
        if ($case) {
            Livewire::test(Table::class)
            ->assertSee($this->b->id)
            ->call("destroyBillboard", $this->b->id)
            ->assertEmitted('deletedBillboards')
            ->assertDontSee($testId);
            $this->assertDatabaseMissing('billboards', ['id' => $testId]);
            } else {
            Livewire::test(Table::class)
            ->assertSee($this->b->id)
            ->call("destroyBillboard", $this->b->id)
            ->assertStatus(403);
            $this->assertTrue(Billboard::whereDescription($this->b->description)->exists());
        }
    }

}
