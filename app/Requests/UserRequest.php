<?php 

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
    }
}