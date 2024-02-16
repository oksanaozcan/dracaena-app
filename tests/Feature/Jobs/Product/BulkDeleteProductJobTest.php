<?php

namespace Tests\Feature\Jobs\Product;

use Tests\TestCase;
use App\Jobs\Product\BulkDeleteProductJob;
use App\Services\ProductService;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BulkDeleteProductJobFailedNotification;

class BulkDeleteProductJobTest extends TestCase
{
    public function test_1_job_is_constructed_correctly()
    {
        $title = 'Sample Product';
        $job = new BulkDeleteProductJob($title);

        $this->assertEquals($title, $job->title);
    }

    public function test_2_handle_method_executes_destroyProductByTitle()
    {
        $title = 'Sample Product';
        $productService = $this->createMock(ProductService::class);
        $productService->expects($this->once())
            ->method('destroyProductByTitle')
            ->with($title);

        $job = new BulkDeleteProductJob($title);
        $job->handle($productService);
    }

    public function test_3_failure_notification_sent_to_admin()
    {
        Notification::fake();

        $title = 'Sample Product';
        $admin = User::find(1);
        $job = new BulkDeleteProductJob($title);
        $exception = new \Exception('Failed to delete product');

        // Simulate a job failure
        $job->failed($exception);

        Notification::assertSentTo(
            $admin,
            BulkDeleteProductJobFailedNotification::class,
            function ($notification) use ($title) {
                return $notification->title === $title;
            }
        );
    }
}
