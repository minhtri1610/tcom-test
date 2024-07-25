<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Space;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return Booking::with('space')->get();
    }

    public function store(Request $request)
    {
        $space = Space::findOrFail($request->space_id);
        $room_id = $space->room_id;

        // Check for time conflicts in the same room
        $conflictingBooking = Booking::whereHas('space', function($query) use ($room_id) {
            $query->where('room_id', $room_id);
        })
        ->where(function($query) use ($request) {
            $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                  ->orWhereRaw('? BETWEEN start_time AND end_time', [$request->start_time])
                  ->orWhereRaw('? BETWEEN start_time AND end_time', [$request->end_time]);
        })
        ->exists();

        if ($conflictingBooking) {
            return response()->json(['error' => 'The room is already booked for the specified time.'], 409);
        }

        $booking = Booking::create($request->all());
        return response()->json($booking, 201);
        
    }

    public function show($id)
    {
        return Booking::with('space')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $space = Space::findOrFail($request->space_id);
        $room_id = $space->room_id;

        // Check for time conflicts in the same room
        $conflictingBooking = Booking::whereHas('space', function($query) use ($room_id) {
            $query->where('room_id', $room_id);
        })
        ->where(function($query) use ($request, $id) {
            $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                  ->orWhereRaw('? BETWEEN start_time AND end_time', [$request->start_time])
                  ->orWhereRaw('? BETWEEN start_time AND end_time', [$request->end_time]);
        })
        ->where('id', '!=', $id)
        ->exists();

        if ($conflictingBooking) {
            return response()->json(['error' => 'The room is already booked for the specified time.'], 409);
        }

        $booking->update($request->all());
        return response()->json($booking, 200);
    }

    public function destroy($id)
    {
        Booking::destroy($id);
        return response()->json(null, 204);
    }
}

