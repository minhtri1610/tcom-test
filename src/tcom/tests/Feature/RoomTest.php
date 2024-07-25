<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Room;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_can_be_created()
    {
        $response = $this->post('/api/rooms', [
            'name' => 'Conference Room',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('rooms', ['name' => 'Conference Room']);
    }

    public function test_room_can_be_retrieved()
    {
        $room = Room::factory()->create();

        $response = $this->get("/api/rooms/{$room->id}");

        $response->assertStatus(200);
        $response->assertJson(['name' => $room->name]);
    }

    public function test_room_can_be_updated()
    {
        $room = Room::factory()->create();

        $response = $this->put("/api/rooms/{$room->id}", [
            'name' => 'Updated Room'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('rooms', ['name' => 'Updated Room']);
    }

    public function test_room_can_be_deleted()
    {
        $room = Room::factory()->create();

        $response = $this->delete("/api/rooms/{$room->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
    }
}

