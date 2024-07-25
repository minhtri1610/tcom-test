<?php
namespace Database\Factories;

use App\Models\Room;
use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpaceFactory extends Factory
{
    protected $model = Space::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'room_id' => Room::factory(),
        ];
    }
}
