<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;

class ApiListController extends Controller
{
    function getlist($id) {
        $board = Boards::find($id);
        return response()->json($board, 200);
    }

    //post로 올거라서 리퀘스트가 있음
    //post는 무조건 값이 넘어와서 이거를 리퀘스트에 담아둠
    function postList(Request $req) {
        //유효성 체크 필요
        //유저 인증 절차
        //토큰 저장용 데이터베이스 따로 필요

        //input안적어도 됨
        $boards = new Boards([ 
            //key=title, content(우리가 보내줘야할 값), 옆이 value
            'title' => $req->title
            ,'content' => $req->content
        ]);

        $boards->save();

        //key가 errorcode이고 
        $arr['errorcode'] = '0';
        $arr['msg'] = 'success';
        //only안에 필요한 값
        $arr['data'] = $boards->only('id', 'title');

        return $arr;

        //json로 data를 가져올 때 배열로 필요한 값을 세팅하고 세팅한 배열을 리턴해줌
        //여기선 laravel이 자동으로 json형태로 변환해줘서 우리가 세팅 안해줘도 되는 거임요
    }
}
