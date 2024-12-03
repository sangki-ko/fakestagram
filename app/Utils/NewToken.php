<?php

namespace App\Utils;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;
use MyEncrypt;
use PDOException;

class NewToken {

    /**
     * 토큰 발급
     * 
     * @param App/models/User $user = user에 데이터를 페이로드에 담기 위해 넣어줌
     * 
     * @return Array [$accessToken, $refreshToken];
     */
    public function createTokens(User $user) {
        $accessToken = $this->createToken($user, env('TOKEN_EXP_ACCESS'));
        $refreshToken = $this->createToken($user, env('TOKEN_EXP_REFRESH'), false);

        return [$accessToken, $refreshToken];
    }

    /**
     * 리프레쉬 토큰 업데이트
     * 
     * @param App/Models/User $userInfo
     * @param string $refreshToken
     * 
     * @return bool true
     */
    public function updateRefreshToken(User $userInfo, string|null $refreshToken) {
        // 유저 모델에 리프레쉬 토큰 변경
        $userInfo->refresh_token = $refreshToken;

        if(!($userInfo->save())) {
            DB::rollBack();
            throw new PDOException('E80');
        }

        return true;
    }

    public function getValueInPayload(string $token, string $key) {
        // 토큰 분리
        // 토큰은 헤더, 페이로드, 시그니처 세개로 이루어져 있다.
        // 우리가 토큰을 받아 페이로드에 담겨져 있는 유저 데이터를 가져올 수 있다.
        // 이 방법으로 데이터 베이스에 있는 리프레쉬 토큰 제거, 해당 유저의 로그아웃을 도운다.
        list($header, $payload, $signature) = $this->explodeToken($token);
        $decodedPayload = json_decode(MyEncrypt::base64UrlDecode($payload));

        if(empty($decodedPayload) || !isset($decodedPayload->$key)) {
            throw new AuthenticationException('E24');
        }

        return $decodedPayload->$key;
    }

    /** 
     * JWT 생성
     * 
     * @param App/models/User $user = user에 데이터
     * @param int $ttl (Time to limit)의 약자이며, env에 설정한 유효시간을 담아준다.
     * @param bool $accessFlg = true / access 토큰인지 아닌지 확인 하는 플레그
     * 
     * @return $header.$payload.$signature 셋을 나눈 것을 인지하기 위해 .을 찍어준다.
     * 
     */
    private function createToken(User $user, int $ttl, $accessFlg = true) {
        $header = $this->createHeader();
        $payload = $this->createPayload($user, $ttl, $accessFlg);
        $signature = $this->createSignature($header, $payload);

        return $header.'.'.$payload.'.'.$signature;
    }

    /** 
     * JWT
     * header 생성
     * 
     * $return string base64URLEncode : MyEncrypt 에서 만든 메소드 base64 형태로 변환해준다.
     * 
     *********** MyEncrypt 파일에 base64Encode 해주는 메소드를 만들 것 ***********
     */

     private function createHeader() {
        $header = [
            'alg' => env('TOKEN_ALG'),
            'typ' => env('TOKEN_TYPE')
        ];

        return MyEncrypt::base64UrlEncode(json_encode($header));
     }





     /**
      * JWT
      * payload 작성
      *
      * @param App/Models/User $user
      * @param int $ttl (Time to limit)의 약자이며, env에 설정한 유효시간을 담아준다.
      * @param bool $accessFlg = true / access 토큰인지 아닌지 확인 하는 플레그
      * 
      * @return string string base64URLEncode : MyEncrypt 에서 만든 메소드 base64 형태로 변환해준다.
      *********** MyEncrypt 파일에 base64Encode 해주는 메소드를 만들 것 ***********
      */

      private function createPayload(User $user, int $ttl, $accessFlg = true) {
            // 현재 시간 습득
            $now = time(); 
            
            $payload = [
                // idt = 유저 pk 번호의 약자 파라미터에 $user의 전체 정보에서 -> user_id(pk번호)만 저장하는 역할
                'idt' => $user->user_id,
                // iat = 발급일자 보통 발급한 시간은 현재 시간을 구해 넣어준다
                'iat' => $now,
                // exp = 파기일자 현재 시간과 유효시간을 더 해 해당 시간이 오면 파기하게 된다.
                'exp' => $now + $ttl,
                // ttl = env에 설정한 유효시간을 담는다.
                'ttl' => $ttl
            ];

            // 액세스 토큰 여부 확인 처리
            if($accessFlg) {
                // 액세스 토큰 여부가 true 즉, 액세스 토큰일 경우 acc(유저의 account(id))를 더 담아주는 행위
                $payload['acc'] = $user->account;
            }

            return MyEncrypt::base64UrlEncode(json_encode($payload));
      }





      /**
       * jwt
       * signature 작성
       * 
       * @param string $header
       * @param string $payload
       * 
       * @return string base64Signature
       * signature 작업은 위에 만든 헤더와 페이로드를 연결하고, salt를 더 해서 만든다.
       * 
       *********** MyEncrypt 파일에 솔트 작업과 문자열을 붙여서 바꿔주는 메소드를 만들 것 ***********
       * hashWithSalt 메소드에 관한 파라미터는 MyEncrypt를 참조
       */

       private function createSignature(string $header, string $payload) {
            return MyEncrypt::hashWithSalt(env('TOKEN_ALG'), $header.env('TOKEN_SECRET_KEY').$payload, MyEncrypt::makeSalt(env('TOKEN_SALT_LENGTH')));
       } 


       /**
        * 토큰 분리
        *
        * 토큰 페이로드에 있는 부분을 잘라서 데이터를 받을 때 헤야 하는 행위
        *
        */

        private function explodeToken($token) {
            $arrToken = explode('.', $token);

            if(count($arrToken) !== 3) {
                throw new AuthenticationException('E23');
            } 

            return $arrToken;
        }


}