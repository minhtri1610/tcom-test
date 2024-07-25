<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Space;
use App\Models\Room;
use App\Models\User;
use App\Models\Booking;

class SpaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_space_can_be_created()
    {
        $room = Room::factory()->create();

        $response = $this->post('/api/spaces', [
            'name' => 'Workspace A',
            'room_id' => $room->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('spaces', ['name' => 'Workspace A']);
    }

    public function test_space_can_be_retrieved()
    {
        $space = Space::factory()->create();

        $response = $this->get("/api/spaces/{$space->id}");

        $response->assertStatus(200);
        $response->assertJson(['name' => $space->name]);
    }

    public function test_space_can_be_updated()
    {
        $space = Space::factory()->create();

        $response = $this->put("/api/spaces/{$space->id}", [
            'name' => 'Updated Space'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('spaces', ['name' => 'Updated Space']);
    }

    public function test_space_can_be_deleted()
    {
        $space = Space::factory()->create();

        $response = $this->delete("/api/spaces/{$space->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('spaces', ['id' => $space->id]);
    }

    public function test_space_booking_conflict_prevention()
    {
        // Tạo người dùng với id 1
        $user = User::factory()->create(['id' => 1]);

        $room = Room::factory()->create();
        $spaceA = Space::factory()->create(['room_id' => $room->id]);
        $spaceB = Space::factory()->create(['room_id' => $room->id]);

        // Tạo booking cho spaceA
        Booking::create([
            'user_id' => $user->id,
            'space_id' => $spaceA->id,
            'start_time' => '2024-07-25 09:00:00',
            'end_time' => '2024-07-25 12:00:00',
        ]);

        // Thử tạo booking cho spaceB trong cùng khoảng thời gian
        $response = $this->post('/api/bookings', [
            'user_id' => $user->id,
            'space_id' => $spaceB->id,
            'start_time' => '2024-07-25 09:00:00',
            'end_time' => '2024-07-25 12:00:00',
        ]);

        $response->assertStatus(409);
        $response->assertJson(['error' => 'The room is already booked for the specified time.']);
    }
}
