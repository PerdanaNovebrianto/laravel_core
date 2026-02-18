<?php 

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Lang;

class AuthRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'  => 'error',
            'message' => 'Validation failed',
            'errors'  => $validator->errors(),
        ], 422));
    }

    public function rules()
    {
        if ($this->routeIs('auth.register')) {
            return [
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|min:8|max:16',
                'name'     => 'required|string|max:255',
                'phone'    => 'nullable|regex:/[0-9]/|min:10|max:14',
                'photo'    => 'nullable|mimes:jpeg,png,jpg|max:2048',
            ];
        }
        
        if ($this->routeIs('auth.login')) {
            return [
                'email'    => 'required|email',
                'password' => 'required|min:8|max:16',
            ];
        }

        if ($this->routeIs('auth.updatePassword')) {
            return [
                'password' => 'required|min:8|max:16',
                'new_password' => 'required|min:8|max:16',
            ];
        }
    }

    public function messages()
    {
        if ($this->routeIs('auth.register')) {
            return [
                'email.required' => Lang::get('validation.required_fill'),
                'email.email'    => Lang::get('validation.format_email'),
                'email.unique'   => Lang::get('validation.unique_email'),
                'password.min'   => Lang::get('validation.min_length', ['min' => 8]),
                'password.max'   => Lang::get('validation.max_length', ['max' => 16]),
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
        
        if ($this->routeIs('auth.login')) {
            return [
                'email.required' => Lang::get('validation.required_fill'),
                'email.email'    => Lang::get('validation.format_email'),
                'password.required' => Lang::get('validation.required_fill'),
                'password.min'   => Lang::get('validation.min_length', ['min' => 8]),
                'password.max'   => Lang::get('validation.max_length', ['max' => 16]),
            ];
        }

        if ($this->routeIs('auth.updatePassword')) {
            return [
                'password.required' => Lang::get('validation.required_fill'),
                'password.min'   => Lang::get('validation.min_length', ['min' => 8]),
                'password.max'   => Lang::get('validation.max_length', ['max' => 16]),
                'new_password.required' => Lang::get('validation.required_fill'),
                'new_password.min'   => Lang::get('validation.min_length', ['min' => 8]),
                'new_password.max'   => Lang::get('validation.max_length', ['max' => 16]),
            ];
        }
    }
}