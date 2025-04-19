<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'postcode' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
            'image' => 'nullable|mimes:jpeg,jpg,png|max:5120'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください',
            'name.string' => '名前は文字列で入力してください',
            'name.max' => '名前は255文字以内で入力してください',
            'postcode.required' => '郵便番号は必須です',
            'postcode.regex' => '郵便番号はXXX-XXXXの形式で入力してください',
            'address.required' => '住所は必須です',
            'address.string' => '住所は文字列で入力してください',
            'address.max' => '住所は255文字以内で入力してください',
            'building.string' => '建物名は文字列で入力してください',
            'building.max' => '建物名は255文字以内で入力してください',
            'image.mimes' => '商品画像はjpeg, jpg, png形式で選択してください',
            'image.max' => '商品画像は5MB以下のものを使用してください'
        ];
    }

    public function validatedData()
    {
        $data = parent::validated();

        if (empty($data['name'])) {
            $data['name'] = auth()->user()->name;
        }

        return $data;
    }
}
