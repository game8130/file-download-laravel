<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionFileTable extends Migration
{
    private $table = 'permission_file';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->mediumIncrements('id')->comment('PK');
            $table->unsignedSmallInteger('group_id')->default(0)->comment('權限 group>id');
            $table->unsignedSmallInteger('file_id')->default(0)->comment('檔案 files>id');
            $table->timestamps();

            $table->unique(['group_id', 'file_id'], 'uk_' . $this->table . '_1');
            $table->index('group_id', 'idx_' . $this->table . '_1');
        });

        DB::statement("ALTER TABLE `" . $this->table . "` COMMENT '檔案權限'");
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
