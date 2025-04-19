<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'categories' => 'required',
            'name' => 'required|string|max:255',
            'condition' => 'required',
            'brand' => 'nullable|string|max:255',
            'detail' => 'required|string|max:255',
            'price' => 'required|integer',
            'image' => 'required|mimes:jpeg,jpg,png|max:5120'
        ];
    }

    public function messages()
    {
        return [
            'categories.required' => 'カテゴリーを選択してください',
            'name.required' => '商品名を入力してください',
            'name.string' => '商品名は文字列で入力してください',
            'name.max' => '商品名は255文字以内で入力してください',
            'condition.required' => '商品の状態を選択してください',
            'brand.string' => 'ブランド名は文字列で入力してください',
            'brand.max' => 'ブランド名は255文字以内で入力してください',
            'detail.required' => '商品の説明を入力してください',
            'detail.string' => '商品の詳細は文字列で入力してください',
            'detail.max' => '商品の説明は255文字以内で入力してください',
            'price.required' => '販売価格を入力してください',
            'price.integer' => '販売価格は整数で入力してください',
            'image.required' => '商品画像を選択してください',
            'image.mimes' => '商品画像はjpeg, jpg, png形式で選択してください',
            'image.max' => '商品画像は5MB以下のものを使用してください'
        ];
    }
}
