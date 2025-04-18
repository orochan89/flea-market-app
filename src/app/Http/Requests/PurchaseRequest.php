<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'payment' => ['required', 'in:0,1'],
            'postcode' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'payment.required' => '支払い方法を選択してください',
            'postcode.required' => '郵便番号は必須です',
            'postcode.regex' => '郵便番号はXXX-XXXXの形式で入力してください',
            'address.required' => '住所は必須です',
            'address.string' => '住所は文字列で入力してください',
            'address.max' => '住所は255文字以内で入力してください',
            'building.string' => '建物名は文字列で入力してください',
            'building.max' => '建物名は255文字以内で入力してください'
        ];
    }
}
