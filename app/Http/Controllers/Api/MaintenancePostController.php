<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MaintenancePostStoreRequest;
use App\Http\Requests\Api\MaintenancePostUpdateRequest;
use App\Http\Resources\Api\MaintenancePostResource;
use App\Models\MaintenanceAttachment;
use App\Models\MaintenancePost;
use App\Services\FileUploadService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenancePostController extends Controller
{
    private PaymentService $paymentService; 

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $loggedInUserPlazaId = request()->user()->plaza_id;
        $query = MaintenancePost::where('plaza_id', $loggedInUserPlazaId)->with('maintenanceAttachments');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }
        $perPage = min($request->input('perPage', 10), 1000);
        $maintenance = $query->paginate($perPage)->through(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'category' => $item->category,
                'cost' => $item->cost,
                'description' => $item->description,
                'attachments'=>$item->maintenanceAttachments->map(function($maintenance){
                  return asset($maintenance->file_url);
                })
            ];
        });

        return response()->json([
            'maintenance' => $maintenance,
        ], 200);
    }

    public function store(MaintenancePostStoreRequest $request): MaintenancePostResource
    {
        $validated = $request->validated();
        $loggedInUser = request()->user();
        $maintenancePost = DB::transaction(function () use ($validated, $request, $loggedInUser) {
            $validated['plaza_id'] = $loggedInUser->plaza_id;
            $validated['created_by'] = $loggedInUser->id;
            $maintenancePost = MaintenancePost::create($validated);
            $uploadedFiles = collect($request->file('attachments'))
                ->map(fn ($file) => FileUploadService::upload($file, 'assets/maintenanceAttachments'))
                ->filter()
                ->values()
                ->toArray();

            foreach ($uploadedFiles as $filename) {
                MaintenanceAttachment::create([
                    'maintenance_post_id' => $maintenancePost->id,
                    'file_url' => $filename,
                    'uploaded_by' => $loggedInUser->id,
                ]);
            }
            $this->paymentService->recordTransaction(
                plazaId: $loggedInUser->plaza_id,
                type: 'debit',
                amount: $validated['cost'],
                description: 'Maintenance Cost',
                userId: $loggedInUser->id,
                resourceType: 'maintenance',
                resourceId: $maintenancePost->id
            );
            return $maintenancePost;
        });

        return new MaintenancePostResource($maintenancePost);
    }

    // public function show(Request $request, MaintenancePost $maintenancePost): Response
    // {
    //     return new MaintenancePostResource($maintenancePost);
    // }

    // public function update(MaintenancePostUpdateRequest $request, MaintenancePost $maintenancePost): Response
    // {
    //     $maintenancePost->update($request->validated());

    //     return new MaintenancePostResource($maintenancePost);
    // }

    // public function destroy(Request $request, MaintenancePost $maintenancePost): Response
    // {
    //     $maintenancePost->delete();

    //     return response()->noContent();
    // }
}
