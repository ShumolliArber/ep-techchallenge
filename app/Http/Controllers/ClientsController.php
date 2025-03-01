<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\ClientPostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

    public function store(ClientPostRequest $request): Client
    {
        return  auth()->user()->clients()->create($request->all());
    }

    public function destroy($client)
    {
        Client::where('id', $client)->delete();

        return 'Deleted';
    }
}
