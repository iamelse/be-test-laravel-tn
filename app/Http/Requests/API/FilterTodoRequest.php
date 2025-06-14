<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class FilterTodoRequest extends FormRequest
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
            'title' => 'nullable|string',
            'assignee' => 'nullable|string',
            'start' => 'nullable|date',
            'end' => 'nullable|date',
            'min' => 'nullable|numeric',
            'max' => 'nullable|numeric',
            'status' => 'nullable|string',
            'priority' => 'nullable|string',
        ];
    }
}
