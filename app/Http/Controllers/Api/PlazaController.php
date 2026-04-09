<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PlazaStoreRequest;
use App\Http\Requests\Api\PlazaUpdateRequest;
use App\Http\Resources\Api\PlazaCollection;
use App\Http\Resources\Api\PlazaResource;
use App\Models\Plaza;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PlazaController extends Controller
{
    public function index(Request $request): PlazaCollection
    {
        $plazas = Plaza::with('residents')->paginate(10);

        return new PlazaCollection($plazas);
    }

    public function store(PlazaStoreRequest $request): PlazaResource
    {
        $plaza = Plaza::create($request->validated());

        return new PlazaResource($plaza);
    }

    public function show(Request $request, Plaza $plaza): PlazaResource
    {
        // dd($plaza);
        return new PlazaResource($plaza);
    }

    public function update(PlazaUpdateRequest $request, Plaza $plaza): Response
    {
        $plaza->update($request->validated());

        return new PlazaResource($plaza);
    }

    public function destroy(Request $request, Plaza $plaza): Response
    {
        $plaza->delete();

        return response()->noContent();
    }
}
