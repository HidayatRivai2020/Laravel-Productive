<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContentRequest extends FormRequest
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
            'category_id' => ['required', 'string', 'exists:categories,id'],
        ];

        if ($this->isMethod('post')) {
            $rules['id'] = ['nullable', 'string', 'unique:contents,id'];
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            $contentId = $this->route('content');
            $rules['id'] = ['nullable', 'string', Rule::unique('contents', 'id')->ignore($contentId)];
        }

        return $rules;
    }
}
