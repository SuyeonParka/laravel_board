<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boards;
use Illuminate\Support\Facades\DB;


class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //데이터 받는 법
        //all:delete돼있는 건 값을 자동으로 제외해줌
        //필요없는 데이터도 가져와줌
        // $result = Boards::all();
        $result = Boards::select(['id', 'title', 'hits', 'created_at', 'updated_at'])->orderBy('hits', 'desc')->get();
        return view('list')->with('data', $result);
        //*인덱스로 가져와서 인덱스는 list라는 자기 뷰?를 가지고 있어서 리다이렉트 안해도 됨
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('write');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        //유저가 어떤 매개를 사용했는지 등의 모든 정보
        //엘로퀀트로 하면 create랑 update는 자동
        //??new하는 이유 insert할 때 사용
        //db에서 가져오는 데이터가 아님, 새로 생성하는 데이터임
        //db에 질의할 수도 없음 
        //그래서 새로운 객체, 새로운 엘로퀀트를 작성해서 new를 사용
        //insert이기 때문에
        $boards = new Boards([
            'title' => $req->input('title')
            ,'content' => $req->input('content')
            ,'hits' => 0
        ]);

        $boards->save();
        //list page로 넘어가기
        //url로 적어야함
        return redirect('/boards');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // + 조회할때마다 조회수 증가
        // 넘어오는 값이 pk 무조건 하나만 넘어올거라서
        // find안에 쿼리가 다 들어있음
        // boards객체가 다 들어가있음
        $boards = Boards::find($id);
        // 각 객체에 프로퍼티처럼 사용가능
        $boards->hits++;
        // 해당 내용 업데이트
        $boards->save();
        // save(업데이트)된 내용도 다시 가져와야함
        // 'data'라는 이름으로 보내줌
        return view('detail')->with('data', Boards::findOrFail($id));
        //fine는 불리언값이 리턴 그리고 프로그래밍이 쭉 이어짐
        //findorfail은 예외처리 됨 그래서 얘가 실패하면 404페이지로함
        //어떤거 선택해서 할지는 알아서 ,,
        //id만 받아와서 show로 화면에 출력
        //show에서 업뎃하고 with으로 다시 셀렉해서 값 받아온거임
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //여기서 이미 select해서 data를 가져왔는데 밑에서 또 가져와서 이구문은 필요없음
        $boards = Boards::find($id);

        //with으로 db에서 받은 정보를 가지고 감
        // return view('edit')->with('data', Boards::findOrFail($id));
        return view('edit')->with('data', $boards);
        //*누르면 업데이트로 감 ->db에서 검색하고 가져옴 -> edit화면(본인이 가지고있는 화면)에서 보여줌
        //*수정페이지에서 수정하고 완료 버튼 누름->업뎃으로 감 ->업뎃은 자기 페이지가 없어서
        //*디테일로 감 (주소 자체가 다름 업뎃이랑) 그래서 리다이렉트함
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //orm은 model객체를 써야 orm
        //지금update구문 select??
        //엘로퀀트랑 orm 차이 : 우리가 만든 모델 객체(Boards)를 사용했냐(orm) 안했냐의 차이
        //DB를 사용해서 한거는 orm이 아님 라라벨에서 지원해주는 기능 사용
        //DB는 쿼리빌더로 db에 질의를 한거

        $boards = Boards::find($id);
        //아래의 쿼리빌더를 orm방식으로 변환한 것(엘로퀀트 모델객체를 이용함, 그걸로 db작업을 함)
        $result = Boards::find($id);
        $result->title = $request->title;
        $result->content = $request->content;
        //주의,db에 문제 없이 쿼리가 커밋됐을 때
        $result->save();
        //input없어도 가져올 수 있음 input지우고 ''없애서 써도 됨
        // DB::table('boards')->where('id', '=', $id)
        //     ->update(['title' => $request->input('title'), 'content' => $request->input('content')]);
        
        //뷰로 리턴하면 문제점 : url이 안바뀜 boards/3으로 상세의 url이 아님
        //업뎃 후에는 다시 셀렉해야함
        //view했을 때 url안바뀌는 이유
        //view = include (그럼 url이 갱신이 안됨, 유저는 안바뀐(교체가 안된) url을 받는 거임)
        //redirect 사용(url이 바껴야된다고 하면 무조건 redirect, ex)로그인하고 메인페이지 가야되는데 url이 로그인url로 있음안됨(view)
        //여기서 with필요없음 : show에서 id만 받게 되니까 id는 세그먼트 파라미터(로 board의 값을 받음)
        //니까 세그먼트 파라미터만 받으면 됨
        return redirect('/boards/'.$id)->with('data', Boards::findOrFail($id));
        //다른 방법
        // return redirect()->route('boards.show', ['board' => $id])->with('data', Boards::findOrFail($id));
        // return view('detail')->with('data', Boards::findOrFail($id));

        //메소드로 기능을 구분 (route:list보면 board.show, update, destroy보면
        //show는 get, update는 put, destroy는 delete로 메소드로 구분돼있음
        //url은 똑같지만 메소드를 보고 구분함)
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //실제로 삭제되면 안되고 deleted_at의 값이 변경돼야해서 업뎃?? 
        //소프트삭제??
        //del, upt, 등은 꼭 트랜잭션 해줘야함
        //destroy는 무조건 pk를 받아야 함 (여기선 id), 소프트 딜리트를 지원
        //delete는 인수가 없어서 우리가 객체를 받아서 처리해줌
        //아래 방법은 둘 다 엘로퀀트로 해서 소프트 딜리트가 저절로 됨(orm을 쓰면 이렇게 소프트 딜리트 기능을 사용할 수 있음!!)
        //DB::delete로 하면 소프트딜리트 안됨. 바로 레코드가 삭제됨

        //아란 언니
        //id로 객체를 가져와서 delete로 삭제
        // $board = Boards::find($id);
        // $board->delete();
        // 위에 두개 체이닝으로
        // $board = Boards::find($id)->delete()
        // 리스트 페이지로 이동(destroy는 페이지가 없으니 redirect로 list페이지로 이동)
        // return redirect('/boards');

        Boards::destroy($id);
        
        return redirect('/boards');

        // $result = DB::table('boards')->delete();

        // $result = DB::table('boards')->where('id', $id)->delete();

        // return view('list');
    }
}
