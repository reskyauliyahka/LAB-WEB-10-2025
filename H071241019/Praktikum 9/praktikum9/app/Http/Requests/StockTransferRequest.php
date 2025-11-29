<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockTransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'quantity.integer' => 'Quantity harus bilangan bulat, misalnya 10 atau -5.',
        ];
    }
}
