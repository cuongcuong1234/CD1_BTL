<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subject_code' => 'required|string|unique:subjects,subject_code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'subject_code.required' => 'Mã môn học là bắt buộc',
            'subject_code.unique' => 'Mã môn học đã tồn tại',
            'name.required' => 'Tên môn học là bắt buộc',
            'credits.required' => 'Số tín chỉ là bắt buộc',
        ];
    }
}
