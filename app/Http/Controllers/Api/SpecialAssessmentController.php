<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SpecialAssessmentStoreRequest;
use App\Http\Requests\Api\SpecialAssessmentUpdateRequest;
use App\Http\Resources\Api\SpecialAssessmentCollection;
use App\Http\Resources\Api\SpecialAssessmentResource;
use App\Models\SpecialAssessment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SpecialAssessmentController extends Controller
{
    public function index(Request $request): Response
    {
        $specialAssessments = SpecialAssessment::all();

        return new SpecialAssessmentCollection($specialAssessments);
    }

    public function store(SpecialAssessmentStoreRequest $request): Response
    {
        $specialAssessment = SpecialAssessment::create($request->validated());

        return new SpecialAssessmentResource($specialAssessment);
    }

    public function show(Request $request, SpecialAssessment $specialAssessment): Response
    {
        return new SpecialAssessmentResource($specialAssessment);
    }

    public function update(SpecialAssessmentUpdateRequest $request, SpecialAssessment $specialAssessment): Response
    {
        $specialAssessment->update($request->validated());

        return new SpecialAssessmentResource($specialAssessment);
    }

    public function destroy(Request $request, SpecialAssessment $specialAssessment): Response
    {
        $specialAssessment->delete();

        return response()->noContent();
    }
}
