<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MaintenancePost;
use App\Models\Payment;
use App\Models\Plaza;
use App\Models\UnitAssesmentPaymentHistory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboardData(Request $request)
    {
        $isChairman = request()->user()->role === 'chairman';
        $isMember = request()->user()->role === 'member';
        $loggedInUserPlazaId = request()->user()->plazaId;
        $loggedInUser = request()->user();
        $masterPoolBalance = Plaza::where('id', $loggedInUserPlazaId)->value('master_pool_balance');
        // $unitId = request()->user()->role === 'member';
        // ten transpanct post,master pool,due for user
        // for chairman master pool,recent activity means who have paid latest latest 10 payments records made by members
        if ($isMember) {

            $maintenancePosts = MaintenancePost::where('plaza_id', $loggedInUserPlazaId)
                ->with('maintenanceAttachments')
                // ->where('unit_id', $loggedInUser->unit_id)
                ->latest()->limit(10)->get()->map(function ($maintenancePost) {
                    return [
                        'title' => $maintenancePost->title,
                        'category' => $maintenancePost->category,
                        'cost' => $maintenancePost->cost,
                        'attachmentts' => $maintenancePost->maintenanceAttachments->map(fn ($attachment) => asset($attachment->file_url)),
                    ];
                });

            $data = [
                'maintenancePost' => $maintenancePosts,
                'masterPoolBalance' => $masterPoolBalance,
            ];
        } elseif ($isChairman) {

            $currentMonthOrCustom = $request->filled('custom_month') ? $request->input('custom_month') : now()->format('Y-m');
            // recent paid
            $query = UnitAssesmentPaymentHistory::where('plaza_id', $loggedInUserPlazaId)
                // ->where('unit_id', $unitId)
                ->select('id', 'amount', 'created_at', \DB::raw("'Assessment' as payment_type"));

            // 2. Union it with the Payment query
            $combinedPayments = Payment::where('plaza_id', $loggedInUserPlazaId)
                // ->where('unit_id', $unitId)
                ->select('id', 'amount', 'created_at', \DB::raw("'Monthly Due' as payment_type"))
                ->union($query) // Merge the queries
                ->latest('created_at') // Sort the combined results
                ->limit(10) // Grab the top 10
                ->get();
            $data = [
                'payments' => $combinedPayments,
                'masterPoolBalance' => $masterPoolBalance,
            ];
        }

        return response([
            'data' => $data,
            'message' => 'Data Retrieved Successfully',
        ], 200);
    }
}
