<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            //email은 중복허용이 안되게 해야함
            //email이 중복허용 안되게 하는데 pk로 안쓰는 이유
            //보통 pk는 숫자형, 정수형으로 주는데 이메일처럼 문자열을 pk로 잡으면 속도 저하
            $table->string('email')->unique();
            //설정 안하면 자동으로 255자리를 잡아줌, 라라벨에서 최소 길이 잡아줘야함
            $table->string('password');
            $table->string('name');
            //email인증(다른 나라는 주민번호가 없음)
            $table->timestamp('email_verified_at')->nullable();
            //로그인 유지하기 기능
            $table->rememberToken();
            //created_at, updated_at을 자동으로 생성해줌
            $table->timestamps();
            //deleted_at로 맹글어줌 sel할때 제외하고 해줌
            //삭제할 때 삭제되는게 아니라 변경해줌
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
