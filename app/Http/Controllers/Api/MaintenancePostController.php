<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MaintenancePostStoreRequest;
use App\Http\Requests\Api\MaintenancePostUpdateRequest;
use App\Http\Resources\Api\MaintenancePostCollection;
use App\Http\Resources\Api\MaintenancePostResource;
use App\Models\MaintenancePost;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MaintenancePostController extends Controller
{
    public function index(Request $request): Response
    {
        $maintenancePosts = MaintenancePost::all();

        return new MaintenancePostCollection($maintenancePosts);
    }

    public function store(MaintenancePostStoreRequest $request): Response
    {
        $maintenancePost = MaintenancePost::create($request->validated());

        return new MaintenancePostResource($maintenancePost);
    }

    public function show(Request $request, MaintenancePost $maintenancePost): Response
    {
        return new MaintenancePostResource($maintenancePost);
    }

    public function update(MaintenancePostUpdateRequest $request, MaintenancePost $maintenancePost): Response
    {
        $maintenancePost->update($request->validated());

        return new MaintenancePostResource($maintenancePost);
    }

    public function destroy(Request $request, MaintenancePost $maintenancePost): Response
    {
        $maintenancePost->delete();

        return response()->noContent();
    }
}
