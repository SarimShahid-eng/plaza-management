<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TransactionLogStoreRequest;
use App\Http\Requests\Api\TransactionLogUpdateRequest;
use App\Http\Resources\Api\TransactionLogCollection;
use App\Http\Resources\Api\TransactionLogResource;
use App\Models\TransactionLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionLogController extends Controller
{
    public function index(Request $request): Response
    {
        $transactionLogs = TransactionLog::all();

        return new TransactionLogCollection($transactionLogs);
    }

    public function store(TransactionLogStoreRequest $request): Response
    {
        $transactionLog = TransactionLog::create($request->validated());

        return new TransactionLogResource($transactionLog);
    }

    public function show(Request $request, TransactionLog $transactionLog): Response
    {
        return new TransactionLogResource($transactionLog);
    }

    public function update(TransactionLogUpdateRequest $request, TransactionLog $transactionLog): Response
    {
        $transactionLog->update($request->validated());

        return new TransactionLogResource($transactionLog);
    }

    public function destroy(Request $request, TransactionLog $transactionLog): Response
    {
        $transactionLog->delete();

        return response()->noContent();
    }
}
