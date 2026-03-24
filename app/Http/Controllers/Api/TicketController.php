<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TicketStoreRequest;
use App\Http\Requests\Api\TicketUpdateRequest;
use App\Http\Resources\Api\TicketCollection;
use App\Http\Resources\Api\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TicketController extends Controller
{
    public function index(Request $request): Response
    {
        $tickets = Ticket::all();

        return new TicketCollection($tickets);
    }

    public function store(TicketStoreRequest $request): Response
    {
        $ticket = Ticket::create($request->validated());

        return new TicketResource($ticket);
    }

    public function show(Request $request, Ticket $ticket): Response
    {
        return new TicketResource($ticket);
    }

    public function update(TicketUpdateRequest $request, Ticket $ticket): Response
    {
        $ticket->update($request->validated());

        return new TicketResource($ticket);
    }

    public function destroy(Request $request, Ticket $ticket): Response
    {
        $ticket->delete();

        return response()->noContent();
    }
}
