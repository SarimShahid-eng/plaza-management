<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SpecialAssessmentStoreRequest;
use App\Http\Requests\Api\SpecialAssessmentUpdateRequest;
use App\Http\Resources\Api\SpecialAssessmentResource;
use App\Models\Plaza;
use App\Models\SpecialAssessment;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SpecialAssessmentController extends Controller
{
    public function index()
    {
        $loggedInUser = request()->user();
        $role = $loggedInUser->role;
        $specialAssessments = SpecialAssessment::where('plaza_id', $loggedInUser->plaza_id)
            ->whereHas('unitAssessmentPayment', function ($q) {
                $q->whereIn('status', ['UNPAID','PARTIAL']);
            })
            ->when($role === 'member', function ($query) use ($loggedInUser) {
                return $query->whereHas('unitAssessmentPayment', function ($q) use ($loggedInUser) {
                    $q->where('unit_id', $loggedInUser->unit_id);
                });
            })
            ->get();
        $specialAssessments->map(function ($specialAssessment) {
            return [
                'id' => $specialAssessment->id,
                'status' => $specialAssessment->status,
                'reason' => $specialAssessment->reason,
                'required_amount' => $specialAssessment->required_amount,
                'required_per_unit' => $specialAssessment->required_per_unit,
                'shortfall' => $specialAssessment->shortfall,
            ];
        });

        // $specialAssessments = SpecialAssessment::all();
        return response()->json([
            'specialAssessments' => $specialAssessments,
        ], 200);
    }

    public function store(SpecialAssessmentStoreRequest $request)
    {
        $validated = $request->validated();
        $loggedInUser = request()->user();
        $validated['plaza_id'] = $loggedInUser->plaza_id;
        $getPlaza = Plaza::find($loggedInUser->plaza_id);
        $query = Unit::where('plaza_id', $loggedInUser->plaza_id)
            ->where('status', 'Occupied');
       $specialAssessment = DB::transaction(function () use ($query, $validated, $getPlaza, $loggedInUser) {

            $occupied_units = $query->count();

            if ($occupied_units == 0) {
                return response()->json([
                    'error' => 'No occupied units found.',
                ], 400);
            }
            // $required amount is what expense is costing means generator or other
            $required_per_unit = ceil($validated['required_amount'] / $occupied_units);

            $shortfall = max(0, $validated['required_amount'] - $getPlaza->master_pool_balance);

            $occupied_units = Unit::where('plaza_id', $loggedInUser->plaza_id)->where('status', 'Occupied')->count();
            $validated['per_unit_amount'] = $required_per_unit;
            $validated['shortfall'] = $shortfall;
            $validated['occupied_units'] = $occupied_units;
            $validated['created_by'] = $loggedInUser->id;


          $assesment=  SpecialAssessment::create($validated);
            $query->increment('due', $required_per_unit, ['updated_at' => now()]);

          return $assesment;

        });
        return response()->json([
                'specialAssesment' => $specialAssessment,
            ], 200);

    }

    // public function show(Request $request, SpecialAssessment $specialAssessment): Response
    // {
    //     return new SpecialAssessmentResource($specialAssessment);
    // }

    // public function update(SpecialAssessmentUpdateRequest $request, SpecialAssessment $specialAssessment): Response
    // {
    //     $specialAssessment->update($request->validated());

    //     return new SpecialAssessmentResource($specialAssessment);
    // }

    public function destroy(Request $request, SpecialAssessment $specialAssessment): Response
    {
        $specialAssessment->delete();

        return response()->noContent();
    }
}
