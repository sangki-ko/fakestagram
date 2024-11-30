<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'account' => ['required', 'between:5,20', 'regex:/^[0-9a-zA-z]+$/']
            ,'password' => ['required', 'between:5,20', 'regex:/^[0-9a-zA-z!@]+$/']
        ];

        // 로그인 할 때 처리
        if($this->routeIs('auth.login')) {
            $rules['account'][] = 'exists:users,account';
        }else if($this->routeIs('user.store')) {
            // 회원가입 할 때 처리
            $rules['account'][] = 'unique:users,account';
            $rules['password_chk'] = ['same:password'];
            $rules['name'] = ['required', 'between:1,20', 'regex:/^[가-힣]+$/u'];
            $rules['gender'] = ['required', 'regex:/^[0-2]{1}$/'];
            $rules['profile'] = ['required', 'image'];
        }


        return $rules;
    }

    // failedValidation : 유효성 검사에서 오류가 났을 때 처리를 해주는 함수
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'success' => false,
            'message' => '유효성 체크 오류',
            'data' => $validator->errors()
        ], 422);

        throw new HttpResponseException($response);
    }
}
