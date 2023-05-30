<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write</title>
</head>
<body>
    {{-- insert페이지 --}}

    @include('layout.errorsvalidate')    

    <form action="{{route('boards.store')}}" method="post">
        @csrf
        <label for="title">제목 : </label>
        <input type="text" name="title" id="title" value="{{old('title')}}">
        <br>
        <label for="content">내용 : </label>
        <textarea name="content" id="content" cols="30" rows="10">{{old('content')}}</textarea>
        <br>
        <button type="submit">작성</button>
    </form>
    {{-- old는 세션에 직전의 값이 있없는지 확인, password는 실패하면 지워야하니까 old안씀 --}}
</body>
</html>