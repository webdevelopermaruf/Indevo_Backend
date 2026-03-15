<?php

namespace App\Http\Requests;

use App\Enums\ExpenseCategories;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class ExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description'    => 'required|string',
            'category'       => ['required', new Enum(ExpenseCategories::class)],
            'amount'         => 'required|numeric',
            'currency'       => 'required|string',
            'expense_date'   => 'required|date',
            'note'          => 'nullable|string',
            'recurring_type' => 'nullable|string|in:once,monthly',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
