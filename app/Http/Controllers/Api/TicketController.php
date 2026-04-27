<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TicketStatusUpdateRequest;
use App\Http\Requests\Api\TicketStoreRequest;
use App\Http\Requests\Api\TicketUpdateRequest;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketStatus;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
                'status' => $item->status,
                'statusHistory' => $item->statusHistory->map(function ($status) {
                    return [
                        'status' => $status->status,
                        'description' => $status->description,
                        'created_at' => $status->created_at,
                    ];
                }),
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
                TicketStatus::create([
                    'ticket_id' => $ticket->id,
                    'status' => 'Pending',
                    'description' => $ticket->description,
                ]);
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

    // only member can update his own ticket only pending ones
    public function update(TicketUpdateRequest $request)
    {
        $validated = $request->validated();
        $ticket = Ticket::with('ticketAttachments')
            ->where('status', 'Pending')
            ->findOrFail($request->update_id);
        $loggedInUser = request()->user();
        // 1. Find the specific record
        $history = $ticket->statusHistory()->first();

        // 2. Update description if provided and timestamps
        if ($history) {
            if ($request->filled('description')) {
                $history->update([
                    'description' => $validated['description'],
                ]);
            }
            $history->touch();
        }
        if ($request->file('attachments')) {
            // $ticket->ticketAttachments()->delete();
            $ticket->ticketAttachments->each(function ($attachment) {
                $path = public_path('assets/ticketAttachments/'.$attachment->file_url);
                if (File::exists($path)) {
                    File::delete($path);
                    $attachment->delete();
                }
            });

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
        }

        $ticket = $ticket->update($validated);

        return $ticket;
    }

    public function updateStatus(TicketStatusUpdateRequest $request)
    {
        $validated = $request->validated();
        $validated['description'] = $validated['description'] ?? null;
        $ticket = Ticket::findOrFail($request->update_id);
        $filtered = array_filter($validated, fn ($value) => ! is_null($value));
        if (! empty($filtered)) {
            $ticket->update($filtered);
        }
        TicketStatus::create([
            'ticket_id' => $ticket->id,
            'status' => $validated['status'],
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json([
            'data' => $ticket->refresh(),

        ]);
    }
}
