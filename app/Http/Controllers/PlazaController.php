<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlazaStoreRequest;
use App\Models\Plaza;
use App\Models\PlazaSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlazaController extends Controller
{
    public function index(Request $request)
    {
        $query = Plaza::with('residents');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                    ->orWhere('city', 'LIKE', "%$search%")
                    ->orWhere('country', 'LIKE', "%$search%");
            });
        }

        $plazas = $query->latest()->paginate(15);

        return view('plaza.index', compact('plazas'));
    }

    public function store(PlazaStoreRequest $request)
    {
        $validated = $request->validated();
        try {

            DB::transaction(function () use ($validated) {

                $plaza = Plaza::UpdateOrCreate(
                    ['id' => $validated['update_id']],
                    $validated);

                PlazaSetting::create([
                    'plaza_id' => $plaza->id,
                    // 'maintenance_approval_threshold',
                    'monthly_dues_amount' => $validated['monthly_dues_amount'],
                    // 'late_fee_percentage',
                    'color' => $validated['color'],
                ]);

                return redirect()->route('plaza.index')
                    ->with('success', 'Plaza created successfully.');
            });
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to plaza. Please try again.']);
        }
    }

    public function edit(Plaza $plaza)
    {
        return view('plaza.index', compact('plaza'));
    }
}
