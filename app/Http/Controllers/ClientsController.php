<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientsController extends Controller
{
    public function index(): View
    {
        $clients = Client::withCount('bookings')->whereUserId(auth()->id())->get();

        return view('clients.index', ['clients' => $clients]);
    }

    public function create()
    {
        return view('clients.create');
    }

    public function show(Request $request, Client $client)
    {
        $clientWithBookings = $client->load(['bookings']);

        if ($request->ajax()) {
            $filter = $request->get('filter');

            if ($filter === 'future') {
                $clientWithBookings = $client->load(['bookings' => function ($query) {
                    $query->where('start', '>', now());
                }]);
            }

            if ( $filter === 'past') {
                $clientWithBookings = $client->load(['bookings' => function ($query) {
                    $query->where('end', '<', now());
                }]);
            }

            return response()->json($clientWithBookings);
        }

        return view('clients.show', ['client' => $clientWithBookings]);
    }

    public function store(ClientRequest $request): Client
    {
        return auth()->user()->clients()->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'address' => $request->get('address'),
            'city' => $request->get('city'),
            'postcode' => $request->get('postcode'),
        ]);
    }

    public function destroy(Client $client): JsonResponse
    {
        if ($client->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $client->delete();

        return response()->json(['message' => 'Client deleted successfully']);
    }
}
