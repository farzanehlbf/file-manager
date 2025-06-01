<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFileRequest extends FormRequest
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
    public function rules()
    {
        return [
            'original_name' => 'sometimes|string|max:255',
            'tags' => 'sometimes|array',
            'tags.*' => 'integer|exists:tags,id',
        ];
    }

    public function messages()
    {
        return [
            'original_name.string' => 'نام فایل باید متنی باشد.',
            'original_name.max' => 'نام فایل نباید بیشتر از 255 کاراکتر باشد.',
            'tags.array' => 'تگ‌ها باید به صورت آرایه باشند.',
            'tags.*.integer' => 'شناسه تگ نامعتبر است.',
            'tags.*.exists' => 'تگ انتخاب شده وجود ندارد.',
        ];
    }
}
