<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
{{-- todo layout 직접 만들깅~ --}}

    {{-- php artisan route:list쳐서 이름 확인 --}}
    <a href="{{route('boards.create')}}">작성하기</a>
    <table>
            <tr>
                <th>글번호</th>
                <th>글제목</th>
                <th>조회수</th>
                <th>등록일</th>
                <th>수정일</th>
            </tr>
        @forelse($data as $item)
            <tr>
                {{-- item하나하나가 class라서 property로 접근 ? --}}
                <td>{{$item->id}}</td>
                <td><a href="{{route('boards.show',['board' => $item->id])}}">{{$item->title}}</a></td>
                <td>{{$item->hits}}</td>
                <td>{{$item->created_at}}</td>
                <td>{{$item->updated_at}}</td>
            </tr>
        {{-- 없으면 --}}
        @empty
            <tr>
                <td></td>
                <td>게시글 없음</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforelse
    </table>
</body>
</html>