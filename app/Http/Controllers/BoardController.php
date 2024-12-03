<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use NewToken;

class BoardController extends Controller
{
    public function index() {
        $boardList = Board::orderBy('created_at',  'DESC')->paginate(8);

        $responseData = [
            'success' => true,
            'msg' => '게시글 획득 성공',
            'boardList' => $boardList->toArray()
        ];

        return response()->json($responseData, 200);
    }

    public function show($id) {
        $board = Board::with('user')->find($id);

        $responseData = [
            'success' => true,
            'msg' => '상세정보 획득 성공',
            'board' => $board->toArray()
        ];

        return response()->json($responseData, 200);
    }

    public function store(Request $request) {
        Log::debug('store', $request->all());
        $insertBoardData = $request->only('content');
        $insertBoardData['user_id'] = NewToken::getValueInPayload($request->bearerToken(), 'idt');
        $insertBoardData['like'] = 0;
        $insertBoardData['img'] = '/'.$request->file('file')->store('img');

        // insert 처리
        $board = Board::create($insertBoardData);

        $responseData = [
            'success' => true,
            'msg' => '게시글 작성 성공',
            'board' => $board->toArray()
        ];

        return response()->json($responseData, 200);
    }

    public function desroy($id) {

        $boardListDelete = Board::destroy($id);

        $responseData = [
            'success' => true,
            'msg' => '게시글 삭제 성공',
            'boardListDelete' => $boardListDelete
        ];

        return response()->json($responseData, 200);
    }
}
