<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    private $table = 'files';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id')->comment('PK');
            $table->unsignedInteger('file_url_id')->default(0)->comment('檔案位置 file_url>id');
            $table->string('name', 100)->comment('標題');
            $table->integer('version')->default(1)->comment('當前版本號碼');
            $table->boolean('status')->default(true)->comment('是否啟用');
            $table->timestamps();
        });
        
        DB::statement("ALTER TABLE `".$this->table."` COMMENT '檔案管理'");
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
