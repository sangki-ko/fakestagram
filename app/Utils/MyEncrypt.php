<?php

namespace App\Utils;

use Illuminate\Support\Str;

class MyEncrypt {

    /** 
     * base64encode URL 인코드
     * 
     * @param string $json / JWT에서 만든 헤더, 페이로드, 시그니처를 인코드 해주는 작업 파라미터엔 해당 3개를 넣는다.
     * 
     * @return string base64 URL encode
     * 
     * rtrim = 오른쪽 끝에 넣어준 해당 '=' 문구나 기호를 삭제 후 리턴
     * strtr = 첫 번째 파라미터에서 두 번쨰 파라미터에 적은 기호들이 있으면 세 번째 파라미터의 값으로 변환 후 리턴
     * base64_encode = base64 형식으로 변환 후 리턴
     */
    
     public function base64UrlEncode(string $json) {
        return rtrim(strtr(base64_encode($json), '+/', '-_'), '=');
     }



    
    /**
     * Salt 작업
     * 특정 길이만큼 랜덤한 문자열을 생성한다.
     * 
     * @param int $saltLength
     * 
     * @return Illuminate\Support\Str Str::random($saltLength);
     * 위 Str::random : 랜덤한 문자열을 생성하는 메소드
     */

     public function makeSalt(int $saltLength) {
        return  Str::random($saltLength);
     }

    



    /** 
     * 문자열 암호화
     * 
     * @param string $alg 알고리즘명
     * @param string $str 암호화 할 문자열
     * @param string $salt 솔트 작업을 완료한 문자열
     * 
     * @return string 암호화가 완료된 문자열
     * 
     * slat를 암호화를 하지 않고 뒤에 붙여주는 이유 : 나중에 리프레쉬 토큰으로 access 토큰을 발급받기 위해 조회를 하게 되는데,
     * 맨 끝 솔트 같은 경우에는 시간이 지나서 발급 받게 되면 기존에 받았던 토큰과 달라지기 때문에 떼줘야 하므로 붙여주기만 한다.
     */

    public function hashWithSalt(string $alg, string $str, string $salt) {
        return hash($alg, $str).$salt;
    }


}