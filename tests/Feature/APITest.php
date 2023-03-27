<?php

namespace Tests\Feature;

use App\Services\API\ShowsAPIClient\V1\Client;
use Mockery\MockInterface;
use Tests\TestCase;

class APITest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function test_endpoint()
    {
        $this->partialMock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('shows')->once()->andReturn([
                'data' => [
                    'id' => 1,
                    'name' => "Show 1"
                ],
            ]);
        });

        $response = $this->get('/api/shows');

        $response->assertStatus(200);
    }
}
