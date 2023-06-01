<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;
use Illuminate\Support\Facades\Validator;

//get이외에는 body를 이용함

class ApiListController extends Controller
{
    function getlist($id) {
        $board = Boards::find($id);
        return response()->json($board, 200);
    }

    //post로 올거라서 리퀘스트가 있음
    //post는 새로운 데이터를 넣음
    //post는 무조건 값이 넘어와서 이거를 리퀘스트에 담아둠
    function postlist(Request $req) {
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
    
    //put : update
    //세그먼트 파라미터는 request에 담기지 않음
    //req에는 body에 담긴 정보가 있음(form 태그 안에 input에 있는 애들임, postman에서 title, content부분)
    //id는 세그먼트 파라미터가 담겨있음
    function putlist(Request $req, $id) {
        //우리가 보내줄 배열(결과 담을 배열)
        //이러한 형태로 json을 보낼 거라고 정의한 것 : data
        $arrData = [
            'code' => '0'
            ,'msg' => ''
        ];

        //필요한 값만 
        //$data안에 title, content, id라는 키가 담김
        //body에서 배열로서 title, content, 세그먼트로 받는 id를 넣음
        //첫번째 아규먼트가 배열로 와야되는데 세그먼트를 배열로 넘겨주기 위해서?
        //data를 만들어줌
        $data = $req->only('title', 'content');
        $data['id'] = $id;
        //body에 x-www뭐시기에다가 해야 얘네가 다 담겼는지 알 수 있음
        // return $data;
        //유효성 체크
        //validator를 새로 생성
        //validator결과를 받을 변수 생성
        //에러가 생기더라도 validator변수에 체크 결과를 저장
        //리셉션을 일으키지 않은 validator사용
        $validator = Validator::make($data, [
            'id'=> 'required|integer|exists:Boards'
            ,'title' => 'required|between:3,30'
            ,'content' => 'required|max:1000'
        ]);
        //위에서 체크가 걸리면 true 아무이상없으면 false
        //validator에서 체크해서 걸렸을 경우
        if($validator->fails()) {
            //e01은 validator에러로 사용
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Validate Error';
            //all()하면 메시지만 추출, 형태는 메시지만 들어가있는 배열
            $arrData['errmsg'] = $validator->errors()->all();
            //api에서 laravel이 리턴했을 때 자동으로 json으로 변경해줌
            //원래는 배열로 리턴
            return $arrData;
        } else {
            //upt처리
            //Boards 객체(불러올때 use적었는지 확인)
            $boards = Boards::find($id);

            //board의 title을 우리가 가져온 req의 title로 변경
            $boards->title = $req->title;
            $boards->content = $req->content;
            $boards->save();
            //codt=0위에서 기본값으로 줘서 필요없음
            $arrData['code'] = '0';
            $arrData['msg'] = 'success';
        }

        return $arrData;

    }

    // delete : delete
    function deletelist($id) {
        $arrData = [
            'code' => '0'
            ,'msg' => ''
        ];
        $data['id'] = $id;
        $validator = Validator::make($data, [
            'id'=> 'required|integer|exists:Boards,id'
        ]);

        if($validator->fails()) {
            //e01은 validator에러로 사용
            $arrData['code'] = 'E01';
            $arrData['msg'] = 'Validate Error';
            //all()하면 메시지만 추출, 형태는 메시지만 들어가있는 배열
            $arrData['errmsg'] = 'id not found';
            //api에서 laravel이 리턴했을 때 자동으로 json으로 변경해줌
            //원래는 배열로 리턴
        } else {
            $boards = Boards::find($id);
            //del flg : $boards
            //만약 id를 찾지 못한다면
            if($boards) {
                Boards::destroy($id);
                $arrData['code'] = '0';
                $arrData['msg'] = 'success';
            } else {
                $arrData['code'] = 'E02';
                $arrData['msg'] = 'already deleted';
            }
            
        }
        //유효성 검사에서 걸리는 거 없이 잘 통과가 되면 $arr배열이 출력
        
        return $arrData;
    }
}

//api는 우리한테 요청이 오고 응답을 해줌