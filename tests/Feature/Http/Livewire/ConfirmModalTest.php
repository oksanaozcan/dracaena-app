<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\ConfirmModal;
use App\Jobs\Product\BulkDeleteProductJob;
use App\Jobs\Tag\BulkDeleteTagJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class ConfirmModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_1_it_can_destroy_checked_tags()
    {
        Queue::fake();

        $checkedTitles = ["title1", "title2", "title3"];
        $currentModel = "Tag";

        Livewire::test(ConfirmModal::class, ['checkedTitles' => $checkedTitles, 'currentModel' => $currentModel])
            ->call('destroyCheckedTags')
            ->assertEmitted('deletedTags');

        Queue::assertPushed(BulkDeleteTagJob::class, function ($job) use ($checkedTitles) {
            return in_array($job->title, $checkedTitles);
        });
    }

    public function test_2_it_can_destroy_checked_products()
    {
        Queue::fake();

        $checkedTitles = ["title1", "title2", "title3"];
        $currentModel = "Product";

        Livewire::test(ConfirmModal::class, ['checkedTitles' => $checkedTitles, 'currentModel' => $currentModel])
            ->call('destroyCheckedProducts')
            ->assertEmitted('deletedProducts');

        Queue::assertPushed(BulkDeleteProductJob::class, function ($job) use ($checkedTitles) {
            return in_array($job->title, $checkedTitles);
        });
    }
}
