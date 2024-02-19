<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFoodTable extends Migration
{
    public function up()
    {
        Schema::table('food', function (Blueprint $table) {
            $table->timestamp('remaining_time_to_accept')->nullable();
            // Các thay đổi khác nếu có
        });
    }

    public function down()
    {
        Schema::table('food', function (Blueprint $table) {
            $table->dropColumn('remaining_time_to_accept');
            // Xoá các thay đổi khác nếu cần thiết
        });
    }
}
