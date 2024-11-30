<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use NewToken;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(UserRequest $request) {
        // 유저 정보 획득
        $userInfo = User::where('account', $request->account)
                        ->first();

        // 비밀번호 체크
        if(!(Hash::check($request->password, $userInfo->password))) {
            throw new AuthenticationException('비밀번호 체크 오류');
        }   

        // 토큰 발행
        list($accessToken, $refreshToken) = NewToken::createTokens($userInfo);
        
        // 리프레쉬 토큰 저장
        NewToken::updateRefreshToken($userInfo, $refreshToken);
        
        $responseData = [
            'success' => 'true',
            'msg' => '로그인 성공',
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
            'data' => $userInfo->toArray()
        ];

        return response()->json($responseData, 200);

        }
}
