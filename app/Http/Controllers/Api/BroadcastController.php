<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BroadcastStoreRequest;
use App\Http\Requests\Api\BroadcastUpdateRequest;
use App\Http\Resources\Api\BroadcastCollection;
use App\Http\Resources\Api\BroadcastResource;
use App\Models\Broadcast;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BroadcastController extends Controller
{
    public function index(Request $request): Response
    {
        $broadcasts = Broadcast::all();

        return new BroadcastCollection($broadcasts);
    }

    public function store(BroadcastStoreRequest $request): Response
    {
        $broadcast = Broadcast::create($request->validated());

        return new BroadcastResource($broadcast);
    }

    public function show(Request $request, Broadcast $broadcast): Response
    {
        return new BroadcastResource($broadcast);
    }

    public function update(BroadcastUpdateRequest $request, Broadcast $broadcast): Response
    {
        $broadcast->update($request->validated());

        return new BroadcastResource($broadcast);
    }

    public function destroy(Request $request, Broadcast $broadcast): Response
    {
        $broadcast->delete();

        return response()->noContent();
    }
}
