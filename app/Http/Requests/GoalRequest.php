<?php

namespace App\Http\Requests;

use App\Enums\GoalCategories;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;

class GoalRequest extends FormRequest
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
            'id' => 'nullable|integer|exists:goals,id',
            'title' => 'required|string|max:255',
            'category' => ['required', new Enum(GoalCategories::class)],
            'deadline_date' => 'required|date_format:Y-m-d',
            'deadline_time' => 'required|date_format:H:i',
            'note' => 'nullable',
            'reminders'=> 'required'
        ];
    }

    protected function prepareForValidation(): void
    {
        // casting
        $this->merge([
            'id' => $this->id !== null ? (int) $this->id : null,
            'reminders' => json_decode($this['reminders']),
        ]);
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
