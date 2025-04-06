<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCarModelRequest extends FormRequest
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
                Rule::unique('car_brand_models', 'title')
                    ->where('car_brand_id', $this->route('brandId'))
            ]
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Название модели обязательно для заполнения',
            'title.max' => 'Название модели не должно превышать 255 символов',
            'title.unique' => 'Модель с таким названием уже существует для этого бренда'
        ];
    }
}
