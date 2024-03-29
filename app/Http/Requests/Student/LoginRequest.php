<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *5
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|exists:students',
            'password' => 'required|min:6',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.exists' => 'Email không tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu cần ít nhất 6 ký tự'
        ];
    }
}
