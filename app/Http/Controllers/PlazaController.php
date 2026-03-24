<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlazaStoreRequest;
use App\Models\Plaza;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlazaController extends Controller
{
    public function index(Request $request): Response
    {
        $plazas = Plaza::all();

        return view('plaza.index');
    }

    public function store(PlazaStoreRequest $request): Response
    {
        $plaza = Plaza::create($request->validated());

        $request->session()->flash('plaza.saved', $plaza->saved);

        return redirect()->route('plaza.index');
    }
}
