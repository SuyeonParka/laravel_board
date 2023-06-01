<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Boards;

class BoardsTest extends TestCase
{
    //php artisan make:test BoardsTest
    //이름의 끝이 Test로 끝날것

    //테스트를 진행할 때 디비를 계속사용하고 끝나면 사용하기 이전으로 지워줌
    //테스트 완료 후 DB초기화를 위한 트레이트(클래스 안에서 사용하는 객체(use가 원래밖에있었는데 이것처럼 안에서 쓰면))
    //1. 첨에 시작할때 트랜잭션 커밋, 롤백해주고
    use RefreshDataBase; 
    //todo 테스트용 db만들기
    //db 마이그레이션
    //2. 그 다음에 db??
    use DatabaseMigrations;    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    //메소드명은 항상 test로 시작해야 작동함
    //test를 할 때 index페이지로 가는 것
    public function test_example_게스트_리다이렉트()
    {
        //처음에 response받는게 get으로 받아온거를 담음test
        //보더에 들어가서 그 결과를 받아서 리다이렉트를 하는 거
        $response = $this->get('/boards');

        $response->assertRedirect('/users/login');
    }

    public function test_index_유저인증() {
        // 테스트용 유저 생성
        $user = new User([
            'email' => 'aa@aa.aa'
            ,'name' => '테스트'
            ,'password' => 'aaaaaaaa'
        ]);
        $user->save();

        //사전의 유저(위의 코드)가 없으면 아래코드에서 에러가 남
        $response = $this->actingAs($user)->get('/boards');

        $this->assertAuthenticatedAs($user);
    }

    public function test_index_유저인증_뷰반환() {
        // 테스트용 유저 생성
        $user = new User([
            'email' => 'aa@aa.aa'
            ,'name' => '테스트'
            ,'password' => 'aaaaaaaa'
        ]);
        $user->save();

        //사전의 유저(위의 코드)가 없으면 아래코드에서 에러가 남
        $response = $this->actingAs($user)->get('/boards');

        $response->assertViewIs('list');
    }

    public function test_index_유저인증_뷰반환_데이터확인() {
        // 테스트용 유저 생성
        $user = new User([
            'email' => 'aa@aa.aa'
            ,'name' => '테스트'
            ,'password' => 'aaaaaaaa'
        ]);
        $user->save();

        $board1 = new Boards([
            'title' => 'test1'
            ,'content' => 'content1'
        ]);
        $board1->save();

        $board2 = new Boards([
            'title' => 'test2'
            ,'content' => 'content2'
        ]);
        $board2->save();

        //사전의 유저(위의 코드)가 없으면 아래코드에서 에러가 남
        //response안에는 다 스트링(문자열)
        $response = $this->actingAs($user)->get('/boards');
        //문자열에서 data가 있는지 없는지 찾음
        $response->assertViewHas('data');
        $response->assertSee('test1');
        $response->assertSee('test2');

    }
}
