<?php 

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;

class UserRequest extends FormRequest
{
    public function rules()
    {
        if ($this->routeIs('user.update')) {
            return [
                'name'     => 'required|string|max:255',
                'phone'    => 'nullable|regex:/[0-9]/|min:10|max:14',
                'photo'    => 'nullable|mimes:jpeg,png,jpg|max:2048',
            ];
        }
    }

    public function messages()
    {
        if ($this->routeIs('user.update')) {
            return [
                'name.required' => Lang::get('validation.required_fill'),
                'name.string' => Lang::get('validation.only_letters'),
                'name.max' => Lang::get('validation.max_length', ['max' => 255]),
                'phone.regex' => Lang::get('validation.format_phone'),
                'phone.min' => Lang::get('validation.min_length', ['min' => 10]),
                'phone.max' => Lang::get('validation.max_length', ['max' => 14]),
                'photo.mimes' => Lang::get('validation.file_type', ['values' => 'jpeg, png, jpg']),
                'photo.max' => Lang::get('validation.file_size', ['max' => 2]),
            ];
        }
    }
}