<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use App\Models\Client;
use Illuminate\Http\Response;
use Tests\TestHelper;

class ClientApiControllerTest extends TestCase
{
    use TestHelper;
    use RefreshDatabase;

    public function test_1_it_stores_client_on_user_created()
    {
        $testClerkId = 'user_' . Str::random(27);

        $response = $this->postJson('/api/clients', [
            'type' => 'user.created',
            'data' => ['id' => $testClerkId],
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('clients', [
            'clerk_id' => $testClerkId,
        ]);
    }


    public function test_2_it_deletes_client_on_user_deleted()
    {
        $client = $this->createClient();

        $response = $this->postJson('/api/clients', [
            'type' => 'user.deleted',
            'data' => ['id' => $client->clerk_id],
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseMissing('clients', [
            'clerk_id' => $client->clerk_id,
        ]);
    }

    public function test_3_it_returns_500_response_on_store_exception()
    {
        $response = $this->postJson('/api/clients', [
            'type' => 'user.created',
            'data' => ['id' => ''],
        ]);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
