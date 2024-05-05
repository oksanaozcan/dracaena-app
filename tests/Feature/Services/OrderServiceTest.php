<?php

namespace Tests\Feature\Services;

use App\Models\Order;
use App\Services\OrderService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Tests\TestHelper;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->or = $this->createOrder();
        $this->do = $this->createSoftDeletedOrder();
        $this->os = new OrderService();
    }

    public function test_destroy_order_throws_500_on_exception()
    {
        $this->dbBeginRollback();
        try {
            $this->os->destroyOrder($this->or);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }

    public function test_restore_order_throws_500_on_exception()
    {
        $this->dbBeginRollback();

        try {
            $this->os->restoreOrder($this->do);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }

    public function test_force_delete_order_throws_500_on_exception()
    {
        $this->dbBeginRollback();

        try {
            $this->os->forceDeleteOrder($this->do);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }
}
