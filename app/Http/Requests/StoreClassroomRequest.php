<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClassroomRequest extends FormRequest
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
            'classroom_code' => 'required|string|unique:classrooms,classroom_code',
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string',
            'teacher_id' => 'nullable|exists:teachers,id',
            'capacity' => 'required|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'classroom_code.required' => 'Mã lớp là bắt buộc',
            'classroom_code.unique' => 'Mã lớp đã tồn tại',
            'name.required' => 'Tên lớp là bắt buộc',
            'academic_year.required' => 'Năm học là bắt buộc',
        ];
    }
}
