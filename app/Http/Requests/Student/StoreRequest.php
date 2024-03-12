<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:students',
            'password' => 'required|min:6|confirmed',
            'first_name' => 'required|string',
            'last_name' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu cần ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không trùng với mật khẩu',
            'first_name.required' => 'Vui lòng nhập Tên',
            'last_name.required' => 'Vui lòng nhập Họ',
        ];
    }
}
