<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable',
            'img_path' => 'nullable|image|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => '商品名は必須項目です。',
            'company_id.required' => '企業IDは必須項目です。',
            'price.required' => '価格は必須項目です。',
            'stock.required' => '在庫は必須項目です。',
            'img_path.image' => '画像は画像ファイルでなければなりません。',
            'img_path.max' => '画像のサイズは2048KB以下である必要があります。',
        ];
    }
}