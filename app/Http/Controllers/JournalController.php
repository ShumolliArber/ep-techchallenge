<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Client;
use App\Journal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function index(Client $client): JsonResponse
    {
        return response()->json($client->journals()->get());
    }

    public function create(): View
    {
        return view('clients.journals.create');
    }

    public function store(Request $request, Client $client, Journal $journal)
    {
        $request->validate([
            'date' => ['required', 'digits:4', 'integer', 'min:1900', 'max:'.date('Y')],
            'text' => 'required|string',
        ]);

        $client->journals()->create([
            'date' => $request->date,
            'text' => $request->text,
        ]);

        return view('clients.show', ['client' => $client->load(['bookings'])]);
    }

    public function destroy(Client $client, Journal $journal): JsonResponse
    {
        $journal->delete();

        return response()->json(['message' => 'Journal deleted successfully']);
    }
}
