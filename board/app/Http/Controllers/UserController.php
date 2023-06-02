<?php
/*************************************
 * 프로젝트명 : laravel_board
 * 디렉토리   : Controllers
 * 파일명     : BoardsController.php
 * 이력       : v001 0530 SY.Park new
 * 버전(소스코드 리뷰 후 마다 버전 상승)
 *************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\User;


class UserController extends Controller
{
    function login() {

        $arr['key'] = 'test';
        $arr['kim'] = 'park';
        Log::emergency('emergency', $arr);
        Log::alert('alert', $arr);
        Log::critical('critical', $arr);
        Log::error('error', $arr);
        Log::warning('warning', $arr);
        Log::notice('notice', $arr);
        Log::info('info', $arr);
        Log::debug('debug', $arr);

        return view('login');
    }

    function loginpost(Request $req) {

        $req->validate([
            'email'    => 'required|email|max:100'  
            //required_unless는 두개를 비교해서 안맞으면 에러냄
            ,'password' => 'required|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);

        // 유저정보 습득
        //req에 email을 넣어줌
        //이메일이 리퀘스트 이메일과 같은 걸 젤 첫번째 거를 가져오겠다는 뜻
        $user = User::where('email', $req->email)->first();
        if(!$user || !(Hash::check($req->password, $user->password))){
            Log::debug($req->password . ' : '.$user->password);
            //일반 str변수로박다다
            $errors = '아이디와 비밀번호를 확인하세요';
            //errors는 특별한 문구라서 error로 변경
            return redirect()->back()->with('error', $errors);
        }
        //유저 인증작업
        //위에서 우리가 가져온 user값 사용
        //알아서 session에 필요한 정보를 올려줌
        Auth::login($user);
        if(Auth::check()) {
            //session에 넣기
            //session에 인증된 회원 pk 등록, session에 id넣음
            //session에 id저장하는 거를 처음에 배열로 넣어서 loginpost를 못가져왔던 거였음
            //로그인 성공
            session($user->only('id'));
            //intended는 아얘 새로운 redirect를 함(필요없는 정보 싹다 클리어)
            return redirect()->intended(route('boards.index'));
        } else {
            $errors = '인증작업 에러';
            return redirect()->back()->with('error', $errors);
        }
    }

    function registration() {
        return view('registration');
    }

    function registrationpost(Request $req) {
        //유효성 체크
        $req->validate([
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'email'    => 'required|email|max:100'  
            //required_unless는 두개를 비교해서 안맞으면 에러냄
            ,'password' => 'required_with:passwordchk|same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ]);

        // 위와 아래는 차이가 없다
        // $data['email'] =$req->input('email');
        $data['name'] = "박".mb_substr($req->name,1);
        $data['email'] = $req->email;
        //Hash : 회원가입할 때 비번 안나옴
        $data['password'] = Hash::make($req->password);

        //insert되고 insert한 결과가 $user에 담김
        $user = User::create($data);
        if(!$user) {
            $errors = '시스템 에러가 발생하여, 회원가입에 실패했습니다.<br>잠시 후에 다시 시도해주세요~.';
            return redirect()
                ->route('users.registration')
                ->with('error', $errors);
                //with의 내용이 session에 저장됨
        }

        //Request $req는 blade파일에 있는 form을 통해 넘어오는 값을 담고
        //$data[]부분 보면 form에서 넘어온 각각의 값이 $data에 배열로 저장돼서
        //$data의 배열이 만들어지고 그 배열을 insert한 결과가 $user에 담김
        
        //회원가입 완료 로그인 페이지로 이동
        return redirect()
            ->route('users.login')
            ->with('success', '회원가입을 완료했습니다.<br>가입하신 아이디와 비밀번호로 로그인해 주세요.');
    }

    

    function logout() {
        //세션 파기
        Session::flush();
        //로그아웃 처리
        Auth::logout();
        return redirect()->route('users.login');
    }

    //탈퇴
    //todo 에러에 대한 처리 프로젝트에 반영
    //보통 pk로 지정
    //(post로 받을거면 피라미터 지정해줘야함)
    public function withdraw() {
        $id = session('id');
        //session에 값있는지 확인하기
        // return session()->all();
        // return var_dump(session()->all(), $id);
        //sofrDelete
        $result = User::destroy($id);
        //result체크
        // return var_dump($result);
        //탈퇴할 때도 삭제
        Session::flush();
        Auth::logout();
        //문제가 없으면
        return redirect()->route('users.login');
    }

    public function useredit() {
        //주석은 내 코드
        // $id = session('id');

        //쌤이 정보불러오는 방법
        //지금 로그인돼있는 user의 엘리퀀트의 id만 뽑음

        $user  = User::find(Auth::User()->id);
        
        //이름은 data
        // return view('useredit')->with('data', User::findOrFail($id));
        return view('useredit')->with('data', $user);
    }

    public function usereditpost(Request $req) {
        //수정한 항목을 담는 배열 가ㅈㅕ오고
        //우리가 바꾸려는 항목만 있는 배열을 주는 이유
        //루프를 최소한으로 돌리기 위해서(arrKey가 최소값?)
        $arrKey = [];

          //기존 데이터 가져오기
        $baseUser  = User::find(Auth::User()->id);
        // $baseUser  = Auth::User();
        
        //기존 비번 틀렸을 때 에러처리
        if(!Hash::check($req->password, $baseUser->password)) {
            return redirect()->back()->with('error', '기존 비밀번호를 확인해 주세요');
        }
        //원래 나의 처리
        // 수정할 항목을 배열에 담는 처리
        //req의 name이 baseUser의 name과 같은지 확인
        if($req->name !== $baseUser->name) {
            //같지 않을 경우에만 배열에 name세팅
            $arrKey[] = 'name';
        }
        if(!isset($req->password)) {
            //같지 않을 경우에만 배열에 name세팅
            $arrKey[] = 'password';
        }

        //유효성체크를 하는 모든 항목 리스트
        //수정하고 싶은 항목 뿐만 아니라 모든 항목이 다 들어가있음
        //그래서 arrKey(우리가 필요한 값만 있는)를 돌림
        $chkList = [
            'name'      => 'required|regex:/^[가-힣]+$/|min:2|max:30'
            ,'bpassword' => 'regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
            ,'password' => 'same:passwordchk|regex:/^(?=.*[a-zA-Z])(?=.*[!@#$%^*-])(?=.*[0-9]).{8,20}$/'
        ];
        


        //유효성 체크할 항목 세팅하는 처리
        $arrchk['bpassword'] = $chkList['bpassword'];
        //그 외의 값들은 루프를 돌려서 필요한 값들만 세팅
        //루프 돌리는 기준이 $arrKey(수정하고 싶은 항목만 들어가있음)
        foreach($arrKey as $val) {
            //$arrKey가 name이면 $arrchk에 name이 담기고 $chkList에 name값이 담김?
            $arrchk[$val] =$chkList[$val];
        }

        // return var_dump($arrchk);
        //유효성 체크
        $req->validate($arrchk);

        //수정할 데이터 셋팅
        foreach($arrKey as $val) {
            if($val === 'password') {
                $baseUser->$val = Hash::make($req->$val);
                //반복문이 돌 때 continue를 만나면 반복문을 멈추고 밑에 루프로 돌아감
                continue;
            }
            //$baseUser의 val 값이 req의 val값에 저장됨
            $baseUser->$val = $req->$val;
        }

        //update
        $baseUser->save();
          //내 소스
        // $id = session('id');
        // $result = User::find($id);
        // $result->name = $req->name;
        // $result->save();

        return redirect()->route('users.useredit');
    }
}