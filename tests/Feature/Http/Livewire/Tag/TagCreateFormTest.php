<?php

namespace Tests\Feature\Http\Livewire\Tag;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;
use Database\Seeders\RoleSeeder;
use Tests\TestHelper;
use App\Http\Livewire\Tag\CreateForm;
use App\Http\Livewire\Tag\Table;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagCreateFormTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->tag = $this->createTag();
        $this->user = $this->createUserWithRole("admin");
        Livewire::actingAs($this->user);
    }

    public function formValidationProvider(): array
    {
        return [
            'title is required' => ['', 2],
            'title is string' => [5646548, 2],
            'title is unique' => ['calathea', 2],
            'title is min 3 char' => ['ts', 2],

            'category_filter_id is required' => ['test for title', null],
        ];
    }

    /**
    * @dataProvider formValidationProvider
    */
    public function test_1_it_validates_form($title, $cf)
    {
        $res = Livewire::test(CreateForm::class)
            ->set('title', $title)
            ->set('category_filter_id', $cf)
            ->call('submitForm');

        $res->assertHasErrors([
            'title' => $title ? null : 'Required',
            'category_filter_id' => $cf ? null : 'Required',
        ]);
    }

    public function test_2_admin_can_store_model()
    {
       $this->canStoreTag();
    }

    public function test_3_manager_can_store_model()
    {
        $manager = $this->createUserWithRole("manager");
        Livewire::actingAs($manager);

        $this->canStoreTag();
    }

    public function test_4_assistant_can_store_model()
    {
        $assistant = $this->createUserWithRole("assistant");
        Livewire::actingAs($assistant);

        $this->canStoreTag();
    }

    public function test_5_can_set_initial_title()
    {
        Livewire::test(CreateForm::class, ['title' => 'foo'])
            ->assertSet('title', 'foo');
    }

    public function test_6_it_displays_success_message_on_tag_create_page_after_creation()
    {
        $this->setFormAndCallMethod()->assertSee('Tag successfully added.');
    }

    public function test_7_model_creation_page_contains_livewire_component()
    {
        $this->get('/tags/create')->assertSeeLivewire(CreateForm::class);
    }

    public function test_8_model_creation_page_doesnt_contain_livewire_component()
    {
        $this->get('/tags/create')->assertDontSeeLivewire(Table::class);
    }

    //edition of model

    public function test_9_admin_can_edit_title_of_model()
    {
        $this->canEditTag();
    }

    public function test_10_manager_can_edit_title_of_model()
    {
        $manager = $this->createUserWithRole("manager");
        Livewire::actingAs($manager);

        $this->canEditTag();
    }

    public function test_11_assistant_can_not_edit_a_model()
    {
        $assistant = $this->createUserWithRole("assistant");
        Livewire::actingAs($assistant);

        $this->canEditTag(false);
    }

    public function test_12_it_redirects_after_edition_to_index_page()
    {
        $res = Livewire::test(CreateForm::class, ['id' => $this->tag->id])
            ->set('title', 'new title')
            ->call('submitForm')
            ->assertRedirect(route("tags.index"));
        $res->assertStatus(200);
    }

    public function test_13_model_edition_page_contains_livewire_component()
    {
        $this->get("/tags/{$this->tag->id}/edit")->assertSeeLivewire(CreateForm::class);
    }

    public function test_14_model_edition_page_doesnt_contain_livewire_component()
    {
        $this->get("/tags/{$this->tag->id}/edit")->assertDontSeeLivewire(Table::class);
    }

    protected function canEditTag(bool $case = true)
    {
        if ($case) {
            $res = Livewire::test(CreateForm::class, ['id' => $this->tag->id])
            ->set('title', 'test of editing')
            ->call('submitForm');

            $res->assertStatus(200);

            $this->assertTrue(Tag::whereTitle('test of editing')->exists());
        } else {
            $res = Livewire::test(CreateForm::class, ['id' => $this->tag->id])
            ->set('title', 'test of editing')
            ->call('submitForm');

            $res->assertStatus(403);

            $this->assertFalse(Tag::whereTitle('test of editing')->exists());
        }
    }

    protected function canStoreTag(bool $case = true)
    {
        if ($case) {
            $res = $this->setFormAndCallMethod();
            $res->assertStatus(200);
            $this->assertTrue(Tag::whereTitle('test title 45644548754')->exists());
        } else {
            $res = $this->setFormAndCallMethod();
            $res->assertStatus(403);
            $this->assertFalse(Tag::whereTitle('test title 45644548754')->exists());
        }
    }

    protected function setFormAndCallMethod()
    {
        $res = Livewire::test(CreateForm::class)
            ->set('title', 'test title 45644548754')
            ->set('category_filter_id', 2)
            ->call('submitForm');
        return $res;
    }
}
