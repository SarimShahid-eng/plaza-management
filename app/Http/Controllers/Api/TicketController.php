<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TicketStoreRequest;
use App\Http\Requests\Api\TicketUpdateRequest;
use App\Http\Resources\Api\TicketResource;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $loggedInUserPlazaId = request()->user()->plaza_id;
        $loggedInUser = request()->user();
        $query = Ticket::where('plaza_id', $loggedInUserPlazaId)->with('ticketAttachments')
            ->when(request()->user()->role === 'member', function ($q) use ($loggedInUser) {
                $q->where('unit_id', $loggedInUser->unit_id);
            });
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }
        $perPage = min($request->input('perPage', 10), 1000);
        $tickets = $query->paginate($perPage)->through(function ($item) {
            return [
                'id' => $item->id,
                'subject' => $item->subject,
                'category' => $item->category,
                'description' => $item->description,
                'attachments' => $item->ticketAttachments->map(function ($ticketAttachment) {
                    return asset($ticketAttachment->file_url);
                }),
            ];
        });

        return response()->json([
            'tickets' => $tickets,
        ], 200);
    }

    public function store(TicketStoreRequest $request)
    {
        $validated = $request->validated();
        $loggedInUser = request()->user();
        // who created ticket
        $validated['user_id'] = $loggedInUser->id;
        $validated['plaza_id'] = $loggedInUser->plaza_id;
        $validated['unit_id'] = $loggedInUser->unit_id;
        try {
            $ticket = DB::transaction(function () use ($validated, $request, $loggedInUser) {
                $ticket = Ticket::create($validated);
                $uploadedFiles = collect($request->file('attachments'))
                    ->map(fn ($file) => FileUploadService::upload($file, 'assets/ticketAttachments'))
                    ->filter()
                    ->values()
                    ->toArray();

                foreach ($uploadedFiles as $filename) {
                    TicketAttachment::create([
                        'ticket_id' => $ticket->id,
                        'file_url' => $filename,
                        'uploaded_by' => $loggedInUser->id,
                    ]);
                }

                return $ticket;
            });

            return response()->json([
                'message' => 'Ticket created successfully',
                'data' => $ticket,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong while creating the ticket.',
                'details' => $e->getMessage(), // Remove this in production for security
            ], 500);
        }

    }

    // public function show(Request $request, Ticket $ticket): Response
    // {
    //     return new TicketResource($ticket);
    // }

    // public function update(TicketUpdateRequest $request, Ticket $ticket): Response
    // {
    //     $ticket->update($request->validated());

    //     return new TicketResource($ticket);
    // }

    // public function destroy(Request $request, Ticket $ticket): Response
    // {
    //     $ticket->delete();

    //     return response()->noContent();
    // }
}
