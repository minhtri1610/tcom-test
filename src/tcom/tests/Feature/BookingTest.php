<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Space;
use App\Models\Room;
use App\Models\Booking;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_can_be_created()
    {
        $user = User::factory()->create(['id' => 1]);
        $room = Room::factory()->create();
        $space = Space::factory()->create(['room_id' => $room->id]);

        $response = $this->post('/api/bookings', [
            'user_id' => $user->id,
            'space_id' => $space->id,
            'start_time' => '2024-07-25 09:00:00',
            'end_time' => '2024-07-25 12:00:00',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('bookings', ['space_id' => $space->id]);
    }

    public function test_booking_conflict_prevention()
    {
        $user = User::factory()->create(['id' => 1]);
        $room = Room::factory()->create();
        $spaceA = Space::factory()->create(['room_id' => $room->id]);
        $spaceB = Space::factory()->create(['room_id' => $room->id]);

        // Tạo booking cho space A
        Booking::create([
            'user_id' => $user->id,
            'space_id' => $spaceA->id,
            'start_time' => '2024-07-25 09:00:00',
            'end_time' => '2024-07-25 12:00:00',
        ]);

        // Thử tạo booking cho space B trong cùng khoảng thời gian
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
