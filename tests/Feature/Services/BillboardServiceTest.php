<?php

namespace Tests\Feature\Services;

use App\Services\BillboardService;
use App\Models\Billboard;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Tests\TestHelper;

class BillboardServiceTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->b = $this->createBillboard();
        $this->bs = new BillboardService();
    }

    public function test_it_catches_exception_of_storing_billboard_and_rolls_back_transaction()
    {
        $descr = 'Test Billboard';
        $cat_id = 1;

        Storage::fake('public');
        $image = UploadedFile::fake()->image('billboard.jpg');

        $this->dbBeginRollback();
        Storage::shouldReceive('disk->put')->andReturnUsing(function () {
            throw new Exception('Failed to store billboard image');
        });

        try {
            $this->bs->storeBillboard($descr, $image, $cat_id);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }

    public function test_it_catches_exception_of_updating_billboard_and_rolls_back_transaction()
    {
        $title = 'Test Billboard';
        $cat_id = 2;

        Storage::fake('public');
        $image = UploadedFile::fake()->image('billboard.jpg');

        $this->dbBeginRollback();
        Storage::shouldReceive('disk->put')->andReturnUsing(function () {
            throw new Exception('Failed to store preview image');
        });

        try {
            $this->bs->updateBillboard($title, $this->b, $image, $cat_id);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }

    public function test_it_catches_exception_of_destroing_billboard_and_rolls_back_transaction()
    {
        $this->dbBeginRollback();

        try {
            $this->bs->destroyBillboard($this->b);
        } catch (Exception $exception) {
            $this->assertEquals(500, $exception->getStatusCode());
            return;
        }

        $this->fail('Exception was not caught.');
    }
}
