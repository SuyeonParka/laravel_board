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
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            // 30글자 제한
            $table->string('title', 30);
            $table->string('content', 2000);
            // 조회수
            // integer(정수) = int
            $table->integer('hits');
            $table->timestamps();
            // delete_at로 이름이 생성됨, 검색 해줌!!!!(물리적 삭제가 아니라 delete쪽에 값이 들어가면서 세팅)
            // 엘로퀀트 사용할 때만 사용 가능
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
        Schema::dropIfExists('boards');
    }
};
