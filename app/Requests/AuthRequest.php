<?php 

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
                'email.required' => 'Email is required',
                'email.unique'   => 'Email already exists',
                'password.min'   => 'Password must be at least 8 characters',
                'password.max'   => 'Password must be less than 16 characters',
                'name.required' => 'Name is required',
                'name.string' => 'Name must be a string',
                'name.max' => 'Name must be less than 255 characters',
                'phone.regex' => 'Phone must be a valid phone number',
                'phone.min' => 'Phone must be at least 10 characters',
                'phone.max' => 'Phone must be less than 14 characters',
                'photo.mimes' => 'Photo must be a valid image file (jpeg, png, jpg)',
                'photo.max' => 'Photo must be less than 2MB',
            ];
        }
        
        if ($this->routeIs('auth.login')) {
            return [
                'email.required' => 'Email is required',
                'email.email'    => 'Email is not valid email address',
                'password.required' => 'Password is required',
                'password.min'   => 'Password must be at least 8 characters',
                'password.max'   => 'Password must be less than 16 characters',
            ];
        }

        if ($this->routeIs('auth.updatePassword')) {
            return [
                'password.required' => 'Password is required',
                'password.min'   => 'Password must be at least 8 characters',
                'password.max'   => 'Password must be less than 16 characters',
                'new_password.required' => 'New password is required',
                'new_password.min'   => 'New password must be at least 8 characters',
                'new_password.max'   => 'New password must be less than 16 characters',
            ];
        }
    }
}