<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipeSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Allow all users to search recipes
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
            'author_email' => ['sometimes', 'nullable', 'email'],
            'keyword' => ['sometimes', 'nullable', 'string', 'max:255'],
            'ingredient' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'author_email.email' => 'The author email must be a valid email address.',
            'keyword.max' => 'The keyword cannot exceed 255 characters.',
            'ingredient.max' => 'The ingredient name cannot exceed 255 characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Trim string inputs
        $this->merge([
            'keyword' => $this->keyword ? trim($this->keyword) : null,
            'ingredient' => $this->ingredient ? trim($this->ingredient) : null,
            'author_email' => $this->author_email ? trim($this->author_email) : null,
        ]);
    }
}
