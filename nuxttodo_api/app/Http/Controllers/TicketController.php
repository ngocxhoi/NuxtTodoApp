<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketCreateRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function store(TicketCreateRequest $request)
    {
        $data = $request->validated();
        $data['creator_id'] = Auth::id();
        $ticket = Ticket::create($data);
        return new TicketResource($ticket);
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('creator', 'members');
        return new TicketResource($ticket);
    }

    public function update(Ticket $ticket, TicketCreateRequest $request)
    {
        $ticket->update($request->validated());
        return new TicketResource($ticket);
    }

    public function destroy(Ticket $ticket, Request $request)
    {
        $ticket->delete();
        return response()->json([
            'message' => 'Ticket deleted successfully'
        ]);
    }

    public function assign(Ticket $ticket, Request $request)
    {
        $data = $request->validate([
            'users' => ['required', 'array'],
        ]);

        $users = User::whereIn('email', $data['users'])->select('id', 'email')->get();

        $ticket->members()->sync($users->pluck('id'));

        return new TicketResource($ticket);
    }

    public function move(Ticket $ticket, Request $request)
    {
        $data = $request->validate([
            'board_id' => ['nullable', 'exists:boards,id'],
            'rank' => ['required', 'integer'],
        ]);

        $ticket = (new TicketServices)->move($data, $ticket);

        return new TicketResource($ticket);
    }
}

