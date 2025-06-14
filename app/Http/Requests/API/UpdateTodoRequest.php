<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTodoRequest extends FormRequest
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
            'title' => 'sometimes|required|string',
            'assignee' => 'nullable|string',
            'due_date' => 'sometimes|required|date|after_or_equal:today',
            'time_tracked' => 'nullable|numeric',
            'status' => 'nullable|in:pending,open,in_progress,completed',
            'priority' => 'nullable|in:low,medium,high',
        ];
    }
}
