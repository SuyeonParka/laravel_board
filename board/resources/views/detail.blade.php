<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
</head>
<body>
    {{-- 경로에 destroy가 아니고 update, show처럼 다른 걸 넣어도 삭제가 되는 이유
        : 메소드가 delete인데 route:list에 delete가 destroy밖에 없어서 destroy url로? 저절로 감
    --}}
    <form action="{{route('boards.destroy', ['board' => $data->id])}}" method="post">
    @csrf
    @method('delete')
    <button type="submit">삭제</button>
    </form>
    <div>
        글번호 : {{$data->id}}
        <br>
        제목 : {{$data->title}}
        <br>
        내용 : {{$data->content}}
        <br>
        등록일자 : {{$data->created_at}}
        <br>
        수정일자 : {{$data->updated_at}}
        <br>
        조회수 : {{$data->hits}}
    </div>
    <button type="button" onclick="location.href='{{route('boards.index')}}'">리스트 페이지로</button>
    <button type="button" onclick="location.href='{{route('boards.edit', ['board'=> $data->id])}}'">수정 페이지로</button>

</body>
</html>