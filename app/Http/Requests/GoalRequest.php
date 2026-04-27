<?php
namespace App\Http\Requests;
use App\Enums\GoalCategories;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Enum;
class GoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'id'            => 'nullable|integer|exists:goals,id',
            'title'         => 'required|string|max:255',
            'category'      => ['required', new Enum(GoalCategories::class)],
            'deadline_date' => 'required|date_format:Y-m-d',
            'deadline_time' => 'required',
            'note'          => 'nullable',
            'reminders'     => 'required',
            'is_completed'  => 'nullable|boolean',
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'id'       => $this->id !== null ? (int) $this->id : null,
            'reminders' => is_string($this['reminders']) ? json_decode($this['reminders']) : ($this['reminders'] ?? []),
        ]);
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors()
            ], 422)
        );
    }
}