<?php

namespace App\Http\Requests\BookStudent;

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
            'book_id' => 'required|integer',
            'number' => 'required|integer|between:1,5'
        ];
    }

    public function messages()
    {
        return [
            'book.required' => 'Vui lòng chọn sách',
            'number.required' => 'Vui lòng chọn số lượng sách',
            'password.between' => 'Chỉ được mượn 1 đên 5 cuốn sách mỗi lần'
        ];
    }
}
