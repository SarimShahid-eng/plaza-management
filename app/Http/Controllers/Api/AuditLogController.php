<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuditLogStoreRequest;
use App\Http\Requests\Api\AuditLogUpdateRequest;
use App\Http\Resources\Api\AuditLogCollection;
use App\Http\Resources\Api\AuditLogResource;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuditLogController extends Controller
{
    public function index(Request $request): Response
    {
        $auditLogs = AuditLog::all();

        return new AuditLogCollection($auditLogs);
    }

    public function store(AuditLogStoreRequest $request): Response
    {
        $auditLog = AuditLog::create($request->validated());

        return new AuditLogResource($auditLog);
    }

    public function show(Request $request, AuditLog $auditLog): Response
    {
        return new AuditLogResource($auditLog);
    }

    public function update(AuditLogUpdateRequest $request, AuditLog $auditLog): Response
    {
        $auditLog->update($request->validated());

        return new AuditLogResource($auditLog);
    }

    public function destroy(Request $request, AuditLog $auditLog): Response
    {
        $auditLog->delete();

        return response()->noContent();
    }
}
