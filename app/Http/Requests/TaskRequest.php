<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
        $rules = [
            'detail' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'status' => ['nullable', 'in:0,1'],
        ];

        // For create form, we use selected_objective_id
        if ($this->has('selected_objective_id')) {
            $rules['selected_objective_id'] = ['required', 'integer', 'exists:objectives,id'];
        } else {
            // For edit form, we use objective_id
            $rules['objective_id'] = ['required', 'integer', 'exists:objectives,id'];
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Convert checkbox to integer: checked = 1, unchecked = 0
        $this->merge([
            'status' => $this->has('status') ? 1 : 0,
        ]);
    }
}
