<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileUrlTable extends Migration
{
    private $table = 'file_url';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id')->comment('PK');
            $table->unsignedInteger('files_id')->comment('檔案管理 files>id');
            $table->text('url')->comment('儲存路徑');
            $table->integer('count')->default(0)->comment('下載次數');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `".$this->table."` COMMENT '檔案位置'");
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
