<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use PDOException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function report(Throwable $th) {
        // report : 우리 시스템이 돌아가다가 에러가 발생하면 에러에 대한 
        // 이력이 남아있어야 고칠 수 있는데, 예외 및 에러기 발생했을 때 호출되며, 주로 로깅이나 외부 서비스에 보고를 하기위한 작업 수행

        Log::info('Report : '.$th->getMessage());
    }
    public function render($request, Throwable $th) {
        // 예외 코드 초기화
        $code = 'E99';

        if($th instanceof AuthenticationException) {
            $code = $th->getMessage();
        }else if($th instanceof PDOException) {
            $code = 'E80';
        }

        $errInfo = $this->context()[$code];

        // Response Data 생성
        $responseData = [
            'success' => false,
            'code' => $code,
            'msg' => $errInfo['msg']
        ];

        // response() : response 객체를 생성하는 메소드
        return response()->json($responseData, $errInfo['status']);
    }

    // 에러 메세지 저장
    public function context() {
        return [
            'E01' => ['status' => 401, 'msg' => '인증 실패'],
            'E80' => ['status' => 500, 'msg' => 'DB 오류가 발생했습니다.'],
            'E99' => ['status' => 500, 'msg' => '시스템 에러가 발생했습니다.'],
        ];
    }
}
