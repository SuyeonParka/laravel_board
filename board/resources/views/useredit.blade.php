@extends('layout.layout')

@section('title', 'Useredit')

@section('contents')
    <h1>회원정보 수정</h1>
    @include('layout.errorsvalidate')
    <form action="{{route('users.useredit.post')}}" method="post">
        @csrf

        <label for="name">이름 : </label>
        <input type="text" name="name" id="name"value="{{$data->name}}">
        <br>
        <label for="email">Email : </label>
        <span>{{$data->email}}</span>
        <br>
        <label for="password">이전 비밀번호 : </label>
        <input type="password" name="bpassword" id="bpassword">
        <br>
        <label for="name">새 비밀번호 : </label>
        <input type="password" name="password" id="password">
        <br>
        <label for="name">새 비밀번호 확인 : </label>
        <input type="password" name="passwordchk" id="passwordchk">
        <br>
        <button type="submit">수정완료</button>
        <button type="button">회원탈퇴</button>
        {{-- window.history.back()이전페이지로 돌아가기 --}}
        <button type="button" onclick="window.history.back()">취소</button>
    </form>
@endsection