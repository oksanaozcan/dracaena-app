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

class TagTableTest extends TestCase
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

    public function test_1_it_can_render_the_component()
    {
        $this->get('/tags')->assertSeeLivewire(Table::class);
    }

    public function test_2_it_can_render_the_component()
    {
        $this->get('/tags')->assertSeeLivewire(CreateForm::class);
    }

    public function test_3_it_can_sort_tags()
    {
        $t1 = Tag::factory()->create(['title' => 'bbbbbbbbbb']);
        $t2 = Tag::factory()->create(['title' => 'aaaaaaaaaaaa']);

        Livewire::test(Table::class)
            ->call('sortBy', 'title')
            ->assertSeeInOrder([$t2->title, $t1->title]);
    }

    public function test_4_it_can_redirect_to_edit_page_of_model()
    {
        Livewire::test(Table::class, ['id' => $this->tag->id])
        ->call("editTag", $this->tag->id)
        ->assertRedirect(route("tags.edit", $this->tag->id));
    }

    public function test_5_admin_can_destroy_tag_usind_table_component()
    {
        $this->canDestroyTag();
    }

    public function test_6_manager_can_destroy_tag_usind_table_component()
    {
        $manager = $this->createUserWithRole("manager");
        Livewire::actingAs($manager);
        $this->canDestroyTag();
    }

    public function test_7_assistant_can_not_destroy_product_usind_table_component()
    {
        $assistant = $this->createUserWithRole("assistant");
        Livewire::actingAs($assistant);
        $this->canDestroyTag(false);
    }

    public function test_8_it_emits_event_tag_added_and_calls_render_method()
    {
        $cf = $this->createCategoryFilter();

        Livewire::test(CreateForm::class)
        ->set('title', 'aaaaaaaaaaaaaaaaaaaaa')
        ->set('category_filter_id', $cf->id)
        ->call('submitForm')
        ->assertEmitted('tagAdded');

        Livewire::test(Table::class)
            ->call('sortBy', 'title')
            ->assertSee('aaaaaaaaaaaaaaaaaaaaa');
    }

    public function test_9_it_can_search_for_tags()
    {
        $t1 = Tag::factory()->create(['title' => 'Tag 1']);
        $t2 = Tag::factory()->create(['title' => 'Tag 2']);

        Livewire::test(Table::class)
            ->set('search', 'Tag 1')
            ->assertSee($t1->title)
            ->assertDontSee($t2->title);
    }

    protected function canDestroyTag(bool $case = true)
    {
        $testId = $this->tag->id;
        if ($case) {
            Livewire::test(Table::class)
            ->call("destroyTag", $this->tag->id)
            ->assertEmitted('deletedTags');
            $this->assertDatabaseMissing('tags', ['id' => $testId]);
            } else {
            Livewire::test(Table::class)
            ->call("destroyTag", $this->tag->id)
            ->assertStatus(403);
            $this->assertTrue(Tag::whereId($this->tag->id)->exists());
        }
    }
}
