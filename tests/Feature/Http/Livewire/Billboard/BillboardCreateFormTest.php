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

class BillboardCreateFormTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->category = $this->createCategory();
        $this->b = $this->createBillboard();
        $this->user = $this->createUserWithRole("admin");
        Livewire::actingAs($this->user);
    }

    public function requiredFormValidationProvider(): array
    {
        return [
            'description is required' => ['', UploadedFile::fake()->image('test_image.jpg'), 1],
            'image is required' => ['test for description', '', 1],
            'category id is required' => ['test for description', UploadedFile::fake()->image('test_image.jpg'), ''],
        ];
    }

    /**
    * @dataProvider requiredFormValidationProvider
    */
    public function test_1_it_validates_form($a, $b, $c)
    {
        Livewire::test(CreateForm::class)
            ->set('description', $a)
            ->set('image', $b)
            ->set('category_id', $c)
            ->call('submitForm')
            ->assertHasErrors([
            'description' => $a ? null : 'Required',
            'image' => $b ? null : 'Required',
            'category_id' => $c ? null : 'Required',
        ]);
    }

    public function test_2_admin_can_store_model()
    {
       $this->canStoreBillboard();
    }

    public function test_3_manager_can_store_model()
    {
        $manager = $this->createUserWithRole("manager");
        Livewire::actingAs($manager);

        $this->canStoreBillboard();
    }

    public function test_4_assistant_can_store_model()
    {
        $assistant = $this->createUserWithRole("assistant");
        Livewire::actingAs($assistant);

        $this->canStoreBillboard();
    }

    public function test_5_can_set_initial_description()
    {
        Livewire::test(CreateForm::class, ['description' => 'foo'])
            ->assertSet('description', 'foo');
    }

    public function test_6_it_displays_success_message_on_billboard_create_page_after_creation()
    {
        $this->setFormAndCallMethod()->assertSee('Billboard successfully added.');
    }

    public function test_7_model_creation_page_contains_livewire_component()
    {
        $this->get('/billboards/create')->assertSeeLivewire(CreateForm::class);
    }

    public function test_8_model_creation_page_doesnt_contain_livewire_component()
    {
        $this->get('/billboards/create')->assertDontSeeLivewire(Table::class);
    }

    // //edition of model

    public function test_9_admin_can_edit_description_of_model()
    {
        $this->canEditBillboard();
    }

    public function test_10_manager_can_edit_description_of_model()
    {
        $manager = $this->createUserWithRole("manager");
        Livewire::actingAs($manager);

        $this->canEditBillboard();
    }

    public function test_11_assistant_can_not_edit_a_model()
    {
        $assistant = $this->createUserWithRole("assistant");
        Livewire::actingAs($assistant);

        $this->canEditBillboard(false);
    }

    public function test_12_it_redirects_after_edition_to_index_page()
    {
        $res = Livewire::test(CreateForm::class, ['id' => $this->b->id])
            ->set('category_id', $this->category->id)
            ->call('submitForm')
            ->assertRedirect(route('billboards.index'));
        $res->assertStatus(200);
    }

    public function test_13_model_edition_page_contains_livewire_component()
    {
        $this->get("/billboards/{$this->b->id}/edit")->assertSeeLivewire(CreateForm::class);
    }

    public function test_12_model_edition_page_doesnt_contain_livewire_component()
    {
        $this->get("/billboards/{$this->b->id}/edit")->assertDontSeeLivewire(Table::class);
    }

    protected function canEditBillboard(bool $case = true)
    {
        if ($case) {
            $res = Livewire::test(CreateForm::class, ['id' => $this->b->id])
            ->set('description', 'test of editing')
            ->call('submitForm');

            $res->assertStatus(200);

            $this->assertTrue(Billboard::whereDescription('test of editing')->exists());
        } else {
            $res = Livewire::test(CreateForm::class, ['id' => $this->b->id])
            ->set('description', 'test of editing')
            ->call('submitForm');

            $res->assertStatus(403);

            $this->assertFalse(Billboard::whereDescription('test of editing')->exists());
        }
    }

    protected function canStoreBillboard()
    {
        $res = $this->setFormAndCallMethod();
        $res->assertStatus(200);
        $this->assertTrue(Billboard::whereDescription('string for testing livewire form for billboard')->exists());
    }

    protected function setFormAndCallMethod()
    {
        $res = Livewire::test(CreateForm::class)
        ->set('description', 'string for testing livewire form for billboard')
        ->set('image', UploadedFile::fake()->image('test_image.jpg'))
        ->set('category_id',  $this->category->id)
        ->call('submitForm');

        return $res;
    }

}
