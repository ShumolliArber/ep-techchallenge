<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\JsonResponse;
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

    public function show(Client $client)
    {
        return view('clients.show', ['client' => $client->load('bookings')]);
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
