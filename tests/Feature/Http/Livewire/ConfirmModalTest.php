<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\ConfirmModal;
use App\Jobs\Product\BulkDeleteProductJob;
use App\Jobs\Tag\BulkDeleteTagJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;
use App\Services\TagService;
use Tests\TestHelper;

class ConfirmModalTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

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

    public function test_destroy_checked_tags_handles_exception()
    {
        $checkedTitles = ["title1", "title2", "title3"];
        $currentModel = "Tag";

        Queue::fake();

        Queue::push(BulkDeleteTagJob::class, $checkedTitles[0]);
        Queue::push(BulkDeleteTagJob::class, $checkedTitles[1]);
        Queue::push(BulkDeleteTagJob::class, $checkedTitles[2]);

        $response = Livewire::test(ConfirmModal::class, ['checkedTitles' => $checkedTitles, 'currentModel' => $currentModel])
            ->call('destroyCheckedTags');

        $this->expectException(\Exception::class);
        $response->throwIfLivewireException();
    }

    public function test_destroy_checked_products_handles_exception()
    {
        $checkedTitles = ["title1", "title2", "title3"];
        $currentModel = "Products";

        Queue::fake();

        Queue::push(BulkDeleteProductJob::class, $checkedTitles[0]);
        Queue::push(BulkDeleteProductJob::class, $checkedTitles[1]);
        Queue::push(BulkDeleteProductJob::class, $checkedTitles[2]);

        $response = Livewire::test(ConfirmModal::class, ['checkedTitles' => $checkedTitles, 'currentModel' => $currentModel])
            ->call('destroyCheckedProducts');

        $this->expectException(\Exception::class);
        $response->throwIfLivewireException();
    }

}
