@if(count($errors) > 0)
    {{-- 연상배열이 아니라서 key가 필요없어서 빼줌 --}}
    @foreach($errors->all() as $error) 
        <div>{{$error}}</div>
    @endforeach
@endif
{{-- $errors 변수에 저장된 모든 오류 메시지를 출력 --}}

{{-- 우리가 설정한 에러 --}}
@if(session()->has('error'))
    <div>{!!session('error')!!}</div>
@endif