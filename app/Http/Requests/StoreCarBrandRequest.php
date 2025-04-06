<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCarBrandRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('car_brands', 'title'),
                'regex:/^[a-zA-Z0-9\s\-]+$/'
            ]
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Название бренда обязательно для заполнения',
            'title.max' => 'Название бренда не должно превышать 255 символов',
            'title.unique' => 'Бренд с таким названием уже существует',
            'title.regex' => 'Название бренда содержит недопустимые символы (разрешены буквы, цифры, пробелы и дефисы)',
        ];
    }
}
