@if(count($errors) > 0)
    {{-- 연상배열이 아니라서 key가 필요없어서 빼줌 --}}
    @foreach($errors->all() as $error) 
        <div>{{$error}}</div>
    @endforeach
@endif