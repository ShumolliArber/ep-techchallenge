<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Client;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function destroy(Client $client, Booking $booking): JsonResponse
    {
        if ($client->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully']);
    }
}
