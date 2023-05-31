@extends('layout.layout')

@section('title', 'List')

@section('contents')
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
@endsection
