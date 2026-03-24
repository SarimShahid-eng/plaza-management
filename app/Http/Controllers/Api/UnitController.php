<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UnitStoreRequest;
use App\Http\Requests\Api\UnitUpdateRequest;
use App\Http\Resources\Api\UnitResource;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UnitController extends Controller
{
    public function index(Request $request): Response
    {
        $units = Unit::where('plaza_id', $plaza_id)->get();

        return new UnitResource($unit);
    }

    public function store(UnitStoreRequest $request): Response
    {
        $unit = Unit::create($request->validated());

        return new UnitResource($unit);
    }

    public function show(Request $request, Unit $unit): Response
    {
        return new UnitResource($unit);
    }

    public function update(UnitUpdateRequest $request, Unit $unit): Response
    {
        $unit->update($request->validated());

        return new UnitResource($unit);
    }

    public function destroy(Request $request, Unit $unit): Response
    {
        $unit->delete();

        return response()->noContent();
    }
}
