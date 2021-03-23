<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    private $table = 'permissions';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->mediumIncrements('id')->comment('PK');
            $table->unsignedSmallInteger('group_id')->default(0)->comment('所屬權限');
            $table->unsignedSmallInteger('func_key')->default(0)->comment('功能Key');
            $table->timestamps();

            $table->unique(['group_id', 'func_key'], 'uk_' . $this->table . '_1');
            $table->index('group_id', 'idx_' . $this->table . '_1');
        });

        DB::statement("ALTER TABLE `" . $this->table . "` COMMENT '功能權限設定'");
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
