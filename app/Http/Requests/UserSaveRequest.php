<?php

namespace App\Http\Requests;

use App\Services\UserCustomerService;
use Illuminate\Foundation\Http\FormRequest;

class UserSaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = request()->route()->parameter('user') ?? '';
        return [
            'nama' => 'required',
            'akses' => 'required',
            'email' => 'required|unique:users,email' . ($id !== '' ? (',' . $id) : ''),
            'password' => 'confirmed' . ($id === '' ? '|required' : '')
        ];
    }
}
