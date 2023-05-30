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
use App\Models\User;

class UserController extends Controller
{
    function login() {
        return view('login');
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
            $errors[] = '시스템 에러가 발생하여, 회원가입에 실패했습니다.';
            $errors[] = '잠시 후에 다시 시도해주세요~.';
            return redirect()
                ->route('users.registration')
                ->with('errors', collect($errors));
        }

        
        //회원가입 완료 로그인 페이지로 이동
        return redirect()
            ->route('users.login')
            ->with('success', '회원가입을 완료했습니다.<br>가입하신 아이디와 비밀번호로 로그인해 주세요.');
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
            $errors[] = '아이디와 비밀번호를 확인하세요';
            return redirect()->back()->with('errors', collect($errors));
        }
        //유저 인증작업
        //위에서 우리가 가져온 user값 사용
        //알아서 session에 필요한 정보를 올려줌
        Auth::login($user);
        if(Auth::check()) {
            //session에 넣기
            //session에 인증된 회원 pk 등록
            session([$req->only('id')]);
            //intended는 아얘 새로운 redirect를 함(필요없는 정보 싹다 클리어)
            return redirect()->intended(route('boards.index'));
        } else {
            $errors[] = '인증작업 에러';
            return redirect()->back()->with('errors', collect($errors));
        }
    }
}