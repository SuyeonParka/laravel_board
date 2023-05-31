<h2>Header</h2>

{{-- auth : 로그인 된 유저일 때 실행가능, 인증된 상태--}}
@auth
    <div><a href="{{route('users.logout')}}">로그아웃</a></div>
    <div><a href="{{route('users.useredit')}}">회원정보 수정</a></div>
@endauth
{{-- guest : login안한 상태, 인증안된 상태 --}}
@guest
    <div><a href="{{route('users.login')}}">로그인</a></div>
@endguest
<hr>
