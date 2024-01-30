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

class CreateFormTest extends TestCase
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

    public function test_2_can_create_model()
    {
        $res = Livewire::test(CreateForm::class)
            ->set('description', 'string for testing livewire form for billboard')
            ->set('image', UploadedFile::fake()->image('test_image.jpg'))
            ->set('category_id',  $this->category->id)
            ->call('submitForm');

        $res->assertStatus(200);
        $this->assertTrue(Billboard::whereDescription('string for testing livewire form for billboard')->exists());
    }

    public function test_3_can_set_initial_description()
    {
        Livewire::test(CreateForm::class, ['description' => 'foo'])
            ->assertSet('description', 'foo');
    }

    public function test_4_it_displays_success_message_on_billboard_create_page_after_creation()
    {
        Livewire::test(CreateForm::class)
            ->set('description', 'string for testing livewire form for billboard')
            ->set('image', UploadedFile::fake()->image('test_image.jpg'))
            ->set('category_id', $this->category->id)
            ->call('submitForm')
            ->assertSee('Billboard successfully added.');
    }

    public function test_5_model_creation_page_contains_livewire_component()
    {
        $this->get('/billboards/create')->assertSeeLivewire(CreateForm::class);
    }

    public function test_6_model_creation_page_doesnt_contain_livewire_component()
    {
        $this->get('/billboards/create')->assertDontSeeLivewire(Table::class);
    }

    // //edition of model

    public function test_7_can_edit_description_of_model()
    {
        $res = Livewire::test(CreateForm::class, ['id' => $this->b->id])
            ->set('description', 'test of editing')
            ->call('submitForm');

        $res->assertStatus(200);

        $this->assertTrue(Billboard::whereDescription('test of editing')->exists());
    }

    public function test_8_it_redirects_after_edition_to_index_page()
    {
        $res = Livewire::test(CreateForm::class, ['id' => $this->b->id])
            ->set('category_id', $this->category->id)
            ->call('submitForm')
            ->assertRedirect(route('billboards.index'));
        $res->assertStatus(200);
    }

    public function test_9_model_edition_page_contains_livewire_component()
    {
        $this->get("/billboards/{$this->b->id}/edit")->assertSeeLivewire(CreateForm::class);
    }

    public function test_10_model_edition_page_doesnt_contain_livewire_component()
    {
        $this->get("/billboards/{$this->b->id}/edit")->assertDontSeeLivewire(Table::class);
    }

}
