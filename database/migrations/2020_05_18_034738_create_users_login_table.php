<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersLoginTable extends Migration
{
    private $table = 'users_login';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id')->comment('PK');
            $table->unsignedInteger('user_id')->default(0)->index()->comment('使用者 users>id');
            $table->string('user_account', 20)->index()->comment('使用者帳號');
            $table->string('login_ip', 46)->default('')->comment('登入IP');
            $table->unsignedTinyInteger('device')->default(1)->comment('使用裝置(1：電腦，2：手機)');
            $table->json('device_info')->nullable()->comment('裝置詳細資訊');
            $table->string('area', 50)->default('')->comment('地區');
            $table->unsignedTinyInteger('status')->default(1)->comment('狀態(1:登入成功, 2:正常登出, 3:強制登出)');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `".$this->table."` COMMENT '使用者登入紀錄'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
