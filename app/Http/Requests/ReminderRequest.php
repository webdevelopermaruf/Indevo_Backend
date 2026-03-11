<?php

namespace App\Http\Requests;

use App\Enums\ReminderCategories;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ReminderRequest extends FormRequest
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
            'description' => 'required|string|max:255',
            'priority'    => ['required', 'in:low,medium,high'],
            'category'    => ['required', new Enum(ReminderCategories::class)],
            'due_time'    => 'nullable|date_format:H:i',
            'due_date'    => 'required|date|after_or_equal:today',
            'recurrence'  => ['required', 'in:once,daily,weekly,monthly'],
            'place'       => 'nullable|string|max:255',
            'note'        => 'nullable|string',
        ];
    }
}
