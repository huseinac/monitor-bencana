<?php

namespace App\Http\Requests\Api;

use App\Services\JwtService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserTokenRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['message' => 'Get Data Failed', 'errors' => $validator->errors()->first()], 422));
    }

    protected function passedValidation()
    {
        $app_key = str_replace( 'base64:', '', env('APP_KEY'));
        $token = $this->bearerToken();
        if (!$token) throw new HttpResponseException(response()->json(['success' => false, 'message' => 'Get Data Failed', 'errors' => 'Bearer token is required'], 401));

        $jwtService = new JWTService();
        $response = $jwtService->verifyJwt($token, $app_key);

        if (!$response) throw new HttpResponseException(response()->json(['success' => false, 'message' => 'Get Data Failed', 'errors' => 'Bearer token is invalid'], 401));

        if ($response['exp'] < time()) throw new HttpResponseException(response()->json(['success' => false, 'message' => 'Get Data Failed', 'errors' => 'Bearer token is expired'], 401));

        $this->merge([
            'user' => $response['user']
        ]);
    }
}
