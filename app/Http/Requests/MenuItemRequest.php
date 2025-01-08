<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemRequest extends FormRequest
{
    public function authorize()
    {
        return session('is_admin');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'is_available' => 'sometimes|boolean',
            'image' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama menu harus diisi',
            'name.max' => 'Nama menu maksimal 255 karakter',
            'description.required' => 'Deskripsi menu harus diisi',
            'price.required' => 'Harga harus diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'price.min' => 'Harga tidak boleh negatif',
            'image.required' => 'URL gambar harus diisi'
        ];
    }
}