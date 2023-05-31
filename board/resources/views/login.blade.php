@extends('layout.layout')

@section('title', 'Login')

@section('contents')
    <h1>로그인</h1>
    @include('layout.errorsvalidate')
    {{-- success가 세션에 있는지를 체크 --}}
    {{-- session() 세션에 잇는 모든 정보 호출 --}}
    {{-- success라는 key가 session에 있는지 확인 --}}
    {{-- true, false로 돌아옴 --}}
    {{-- br태그 먹히게 할려면 {!! 사용 대신 보안에 취약 --}}
    <div>{!!session()->has('success') ? session('success') : ""!!}</div>
    <form action="{{route('users.login.post')}}" method="post">
        @csrf
        <label for="email">Email : </label>
        <input type="text" name="email" id="email">

        <label for="password">password : </label>
        <input type="password" name="password" id="password">
        <br>
        <button type="submit">Login</button>
        <button type="button" onclick="location.href = '{{route('users.registration')}}'">Registration</button>
    </form>
@endsection