<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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

        $bookId = $this->route('book')->id;
        return [

          'ISBN' => 'sometimes|string|size:13|unique:books,ISBN,' . $bookId,
            'title' => 'sometimes|string|max:70',
            'price' => 'sometimes|numeric|min:0',
            'mortgage' => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'cover' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}
