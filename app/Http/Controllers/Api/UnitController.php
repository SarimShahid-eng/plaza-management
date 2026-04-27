<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UnitPaidAmountStoreRequest;
use App\Http\Requests\Api\UnitStoreRequest;
use App\Http\Requests\Api\UnitUpdateRequest;
use App\Http\Resources\Api\UnitResource;
use App\Models\MonthlyDue;
use App\Models\Payment;
use App\Models\PlazaSetting;
use App\Models\SpecialAssessment;
use App\Models\Unit;
use App\Models\UnitAssesmentPayment;
use App\Models\UnitAssesmentPaymentHistory;
use App\Models\User;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $loggedInUserPlazaId = request()->user()->plaza_id;
        $query = Unit::where('plaza_id', $loggedInUserPlazaId)->with('resident:id,full_name,unit_id');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('unit_number', 'like', "%{$search}%")
                    ->orWhereHas('resident', function ($sub) use ($search) {
                        $sub->where('full_name', 'like', "%{$search}%");
                    });
            });
        }
        if ($request->filled('status')) {
            $status = $request->input('status');
            $query->where('status', $status);
        }
        $perPage = min($request->input('perPage', 10), 1000);
        $units = $query->paginate($perPage)->through(function ($item) {
            return [
                'id' => $item->id,
                'unit_number' => $item->unit_number,
                'floor' => $item->floor,
                'user' => $item->resident->full_name ?? null,
                'status' => $item->status,
                'monthly_due' => $item->monthly_dues_amount,
                'due' => $item->due,
                // 'latestStatus' => $item->latestHistory ? [
                //     'id' => $item->latestHistory->id,
                //     'status' => $item->latestHistory->status,
                //     'notes' => $item->latestHistory->notes,
                //     'date' => $item->latestHistory->created_at->format('d M, Y'),
                //     'changed_by' => $item->latestHistory->changed_by,
                // ] : null,

                // 'histories' => $item->histories->map(function ($history) {
                //     return [
                //         'id' => $history->id,
                //         'status' => $history->status,
                //         'notes' => $history->notes,
                //         'date' => $history->created_at->format('d M, Y'),
                //         'changed_by' => $history->changed_by,
                //     ];
                // }),
            ];
        });

        return response()->json([
            'units' => $units,
        ], 200);
    }

    public function store(UnitStoreRequest $request)
    {
        $validated = $request->validated();
        $loggedInUser = $request->user(); // Correctly grabbing the user object
        $lastUnit = Unit::where('plaza_id', $loggedInUser->plaza_id)
            ->where('unit_number', 'like', $validated['prefix'].'-%')
            ->orderByDesc('id') // or unit_number if properly formatted
            ->first();

        if ($lastUnit) {
            // Extract number from "ABC-10"
            $lastNumber = (int) explode('-', $lastUnit->unit_number)[1];
        } else {
            $lastNumber = 0;
        }
        // Use a transaction to ensure all-or-nothing data integrity
        $units = DB::transaction(function () use ($validated, $loggedInUser, $lastNumber) {

            $unitModels = collect()->times($validated['count'], function ($i) use ($validated, $loggedInUser, $lastNumber) {
                $sequenceNumber = $lastNumber + $i;

                $unit = Unit::create([
                    'plaza_id' => $loggedInUser->plaza_id,
                    'unit_number' => $validated['prefix'].'-'.$sequenceNumber,
                    'floor' => $validated['floor'] ?? null,
                    'monthly_dues_amount' => $this->getMonthlyDuesByPlazaUnit(),
                    'due' => 0,
                ]);

                return $unit;
            });

            return new EloquentCollection($unitModels);
        });

        return UnitResource::collection($units);

    }

    private function getMonthlyDuesByPlazaUnit()
    {
        // $request = new Request;
        $loggedInUser = request()->user();
        // dd($loggedInUser);
        $plazaFees = PlazaSetting::where('plaza_id', $loggedInUser->plaza_id)->first()->monthly_dues_amount;

        return $plazaFees;
    }

    public function show(Request $request, Unit $unit): UnitResource
    {
        // 1. Load the relationship to avoid N+1 and ensure history shows up
        $unit->load(['histories', 'resident']);

        return new UnitResource($unit);
    }

    public function update(UnitUpdateRequest $request): UnitResource
    {
        $validated = $request->validated();

        $unit = Unit::findOrFail($validated['unit_id']);
        DB::transaction(function () use ($unit, $validated, $request) {
            $unit->update($validated);
            if (isset($validated['status'])) {
                $unit->histories()->create([
                    'user_id' => $validated['user_id'] ?? $unit->user_id,
                    'plaza_id' => $request->user()->plaza_id,
                    'status' => $validated['status'] ?? 'Occupied',
                    'move_in' => now(),
                    'notes' => 'Status updated via API',
                    'changed_by' => $request->user()->id,
                ]);
            }
        });

        return new UnitResource($unit->load('histories'));
    }

    public function destroy(Unit $unit): Response
    {
        $unit->delete();

        return response()->noContent();
    }

    public function paidAmount(UnitPaidAmountStoreRequest $request)
    {
        // get current amount fined by admin;
        $validated = $request->validated();
        $unit = Unit::find($validated['unit_id']);
        $userId = $validated['user_id'];
        $requestedUser = User::find($userId);
        if ($requestedUser->unit_id === null) {
            return response()->json([
                'error' => 'User must be assigned in order to pay',
            ], 422);
        }

        if ($requestedUser->unit_id != $validated['unit_id']) {
            return response()->json([
                'error' => 'User must Pay for his unit only',
            ], 422);
        }

        if ($validated['amount_type'] === 'monthly' || $validated['amount_type'] === 'custom') {
            $currentMonthOrCustom = $validated['amount_type'] === 'custom' ? $validated['custom_month'] : now()->format('Y-m');
            // 2026-03
            // if last month remaining amount exist than that amount-current coming coming amount else monthly dues - current coming amount= remaining amount
            $latestRemaining = MonthlyDue::where('unit_id', $validated['unit_id'])
            // if monthly means paying for current month
                ->where('month', $currentMonthOrCustom)
                ->latest('id')->first();

            $monthlyDuesAmount = (float) $unit->monthly_dues_amount;
            $latestRemainingCurrent = $latestRemaining ? $latestRemaining->remaining_amount : $monthlyDuesAmount;
            // dd($latestRemainingCurrent);
            $remainingAmount = $latestRemainingCurrent - $validated['amount'];
            if ($latestRemaining && (float) $latestRemaining->remaining_amount == 0) {
                return response()->json([
                    'error' => 'You have already paid for this month',
                ], 422);
            }
            if ($latestRemainingCurrent < $validated['amount']) {
                return response()->json([
                    'error' => 'Amount must be equals or less',
                ], 422);
            }

            $userDate = Carbon::parse($request->input('custom_month'))->startOfMonth();

            // Apply startOfMonth() to the stored date too
            if (! $userDate->greaterThanOrEqualTo($unit->history->move_in->copy()->startOfMonth())) {
                return response()->json([
                    'error' => 'Payment month cannot be before the move-in month.',
                ], 422);
            }

            DB::transaction(function () use ($validated, $userId, $request, $monthlyDuesAmount, $remainingAmount, $unit, $currentMonthOrCustom) {
                // tracks payment amount
                $monthlyDue = MonthlyDue::firstOrCreate(
                    [
                        'unit_id' => $validated['unit_id'],
                        'plaza_id' => $request->user()->plaza_id,
                        'month' => $currentMonthOrCustom,
                    ],
                    [
                        'user_id' => $userId,
                        'monthly_amount' => $monthlyDuesAmount,
                        'paid_amount' => 0,
                        'remaining_amount' => $remainingAmount,
                        'status' => 'UNPAID',
                        'due_date' => now()->addMonth()->day(15),
                    ]
                );
                //
                // Update if already exists
                $monthlyDue->increment('paid_amount', (float) $validated['amount']);
                $remainingAmount = $monthlyDue->monthly_amount - $monthlyDue->paid_amount;
                $monthlyDue->update([
                    'user_id' => $userId,
                    'remaining_amount' => max(0, $remainingAmount),
                    'status' => $remainingAmount <= 0 ? 'Paid' : ($monthlyDue->paid_amount > 0 ? 'Partial' : 'Unpaid'),
                    'payment_date' => now(),
                ]);
                // if ($remainingAmount <= 0) {

                    // Create Payment record for monthly only
                    Payment::create([
                        'user_id' => $userId,
                        'unit_id' => $validated['unit_id'],
                        'plaza_id' => $request->user()->plaza_id,
                        'monthly_due_id' => $monthlyDue->id,
                        'amount' => $validated['amount'],
                        'payment_type' => 'Cash', // Cash, Bank Transfer, etc.
                        'recorded_by' => $request->user()->id,
                        'payment_month' => $currentMonthOrCustom,
                    ]);
                // }

                $unit->update(['due' => max(0, $remainingAmount)]);
                $this->paymentService->recordTransaction(
                    plazaId: $request->user()->plaza_id,
                    type: 'credit',
                    amount: $validated['amount'],
                    description: 'Monthly dues payment',
                    userId: $request->user()->id,
                    resourceType: 'monthly_dues',
                    resourceId: $monthlyDue->id  // ✓ MonthlyDue ID
                );
            });

        } elseif ($validated['amount_type'] === 'assessment') {
            $specialAssessment = SpecialAssessment::find($validated['assessment_id']);
            $unitAssesmentPayment = UnitAssesmentPayment::where('assessment_id', $validated['assessment_id'])->first();
            $latestRemainingCurrent = $unitAssesmentPayment ? $unitAssesmentPayment->remaining_amount : $specialAssessment->per_unit_amount;
            // assesed amount or remaining amount
            $remainingAmount = $latestRemainingCurrent - $validated['amount'];
            if ($latestRemainingCurrent < $validated['amount']) {
                return response()->json([
                    'error' => 'Amount must be equals or less',
                ], 422);
            }
            if ($unitAssesmentPayment && (float) $unitAssesmentPayment->remaining_amount == 0) {
                return response()->json([
                    'error' => 'You have already paid for this assesment',
                ], 422);
            }

            DB::transaction(function () use ($validated, $specialAssessment, $userId, $remainingAmount, $unit) {
                $assessment = UnitAssesmentPayment::firstOrCreate([
                    'unit_id' => $validated['unit_id'],
                    'plaza_id' => request()->user()->plaza_id,
                    'assessment_id' => $validated['assessment_id'],
                ],
                    [
                        'user_id' => $userId,
                        'assessed_amount' => $specialAssessment->per_unit_amount,
                        'paid_amount' => 0,
                        'remaining_amount' => $remainingAmount,
                        'status' => 'UNPAID',
                    ]);
                $assessment->increment('paid_amount', (float) $validated['amount']);
                $remainingAmount = $assessment->assessed_amount - $assessment->paid_amount;

                if ($remainingAmount <= 0) {
                    $status = 'Paid';
                } elseif ($remainingAmount < $assessment->assessed_amount) {
                    $status = 'Partial';
                } else {
                    $status = 'Unpaid';
                }

                $assessment->update([
                    'user_id' => $userId,
                    'remaining_amount' => max(0, $remainingAmount),
                    'status' => $status,
                    'payment_date' => now(),
                ]);
                $totalRemaining = MonthlyDue::forUnit($validated['unit_id'])
                    ->whereIn('status', ['UNPAID', 'PARTIAL', 'OVERDUE'])
                    ->sum('remaining_amount') +
                    // here rightnow
                                 UnitAssesmentPayment::forUnit($validated['unit_id'])
                                     ->whereIn('status', ['UNPAID', 'PARTIAL', 'OVERDUE'])
                                     ->sum('remaining_amount');

                $unit->update(['due' => $totalRemaining]);
                // if ($remainingAmount <= 0) {

                    // Create Payment record for monthly only
                    UnitAssesmentPaymentHistory::create([
                        'unit_payment_id' => $assessment->id,
                        'assessment_id' => $validated['assesment_id'],
                        'user_id' => $userId,
                        'unit_id' => $validated['unit_id'],
                        'plaza_id' => request()->user()->plaza_id,
                        'amount' => $validated['amount'],
                        'payment_type' => 'Cash',
                        'recorded_by' => request()->user()->id,
                        'payment_month' => now()->format('Y-m'),
                    ]);
                // }

                $this->paymentService->recordTransaction(
                    plazaId: request()->user()->plaza_id,
                    type: 'credit',
                    amount: $validated['amount'],
                    description: 'Assessment payment',
                    userId: request()->user()->id,
                    resourceType: 'assessment',
                    resourceId: $assessment->id  // ✓ Assessment ID
                );
            });

        }

        return response()->json(['success' => true, 'unit' => $unit]);

    }

    public function assignMember(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $unit = Unit::findOrFail($validated['unit_id']);
        if ($unit->status === 'Occupied') {
            return response()->json([
                'error' => 'Its already Occupied',
            ], 422);
        }

        $user = User::findOrFail($validated['user_id']);
        // email,password
        if ($user->role != 'member') {
            return response()->json([
                'message' => 'Assign user must be a member'], 422);
        }

        DB::transaction(function () use ($unit, $validated, $request, $user) {
            $unit->update([
                'status' => 'Occupied',
                'due' => $this->getMonthlyDuesByPlazaUnit(),
            ]);
            $user->update([
                'unit_id' => $validated['unit_id'],
            ]);
            $unit->histories()->create([
                'plaza_id' => $request->user()->plaza_id,
                'user_id' => $user->id,
                'move_in' => now(),
                'notes' => 'assigning member',
                'changed_by' => $request->user()->id,
            ]);
        });

        return response()->json([
            'message' => 'Memeber On Board Successfully',
            'unit' => new UnitResource($unit->load(['resident'])),
        ], 200);
    }

    public function revokeMember(Request $request)
    {
        $validate = $request->validate([
            'unit_id' => 'required|exists:units,id',
        ]);
        $unit = Unit::find($validate['unit_id']);
        if ($unit->status === 'Occupied') {

            $unit->histories()->update([
                'move_out' => now(),
                'due_at_checkout' => $unit->due,
                'notes' => 'moving out member',
                'changed_by' => $request->user()->id,
            ]);

            $unit->update([
                // 'user_id' => null,
                'status' => 'Vacant',
                'due' => 0,
            ]);
            $unit->user->update([
                'unit_id' => null,
            ]);
        } else {
            return response()->json([
                'error' => 'Its already Vacant',
            ], 422);
        }

        return response()->json([
            'message' => 'Member Moved Out Successfully',
            'unit' => new UnitResource($unit->load(['resident'])),
        ], 200);
    }
}
