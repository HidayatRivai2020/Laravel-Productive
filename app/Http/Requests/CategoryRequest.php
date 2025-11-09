<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'desc' => ['nullable', 'string'],
        ];

        // ID may be provided on create; it must be unique. On update we ignore the current model.
        if ($this->isMethod('post')) {
            $rules['id'] = ['nullable', 'string', 'unique:categories,id'];
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            $categoryId = $this->route('category');
            $rules['id'] = ['nullable', 'string', Rule::unique('categories', 'id')->ignore($categoryId)];
        }

        return $rules;
    }
}
