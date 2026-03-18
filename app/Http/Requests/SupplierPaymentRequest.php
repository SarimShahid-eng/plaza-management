<?php

namespace App\Http\Requests;

use App\Models\Purchase;
use Illuminate\Foundation\Http\FormRequest;

class SupplierPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $supplierId = $this->input('supplier_id');

        $firstPurchaseDate = Purchase::where('supplier_id', $supplierId)
            ->min('purchase_date');

        return [
            'updateId' => 'nullable|integer|exists:supplier_payments,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'amount' => 'required|numeric|min:1',
            'payment_date' => [
                'required',
                'date',
                $firstPurchaseDate ? 'after_or_equal:'.$firstPurchaseDate : null,
            ],
            // 'payment_date' => 'required|date',
            'note' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        $supplierId = $this->input('supplier_id');

        $firstPurchaseDate = Purchase::where('supplier_id', $supplierId)
            ->min('purchase_date');

        return [
            'supplier_id.required' => 'Please select a supplier.',
            'supplier_id.exists' => 'Selected supplier is invalid.',
            'amount.required' => 'Payment amount is required.',
            'amount.numeric' => 'Amount must be a number.',
            'payment_date.required' => 'Payment date is required.',
            'payment_date.after_or_equal' => 'Payment date cannot be before the first purchase date ('.
            ($firstPurchaseDate ? \Carbon\Carbon::parse($firstPurchaseDate)->format('d M Y') : 'N/A').').',
        ];
    }
}
