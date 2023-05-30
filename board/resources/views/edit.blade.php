<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
</head>
<body>
    {{-- 수정은 put --}}
    {{-- 업데이트 페이지 --}}
    {{-- php artisan route:list 참고하면 update에 세그먼트 파라미터 있음 --}}
    {{-- ['board' => $data->id] 세그먼트 파라미터로 줌 --}}
    {{-- 근데 왜 얘를 세그먼트 파라미터로줘 --}}
    {{-- 해당하는 아이디에 맞는 페이지로 넘어가게 쟤를 세그먼트 파라미터로 줌 --}}
    @if(count($errors) > 0)
        @foreach($errors->all() as $error)
            <div>{{$error}}</div>
        @endforeach       
    @endif
    <form action="{{route('boards.update', ['board' => $data->id])}}" method="post">
        @csrf
        @method('put')
        <label for="title">제목 : </label>
        <input type="text" name="title" id="title" value="{{count($errors) > 0 ? old('title') : $data->title}}">
        <br>
        <label for="content">내용 : </label>
        {{-- textarear는 tab하면 개행 들어가서 조심?? --}}
        <textarea name="content" id="content" cols="30" rows="10">{{count($errors) > 0 ? old('content') : $data->content}}</textarea>
        <br>
        <button type="submit">수정</button>
        <button type="button" onclick="location.href='{{Route('boards.show', ['board' => $data->id])}}'">취소</button>
        
    </form>
    
</body>
</html>