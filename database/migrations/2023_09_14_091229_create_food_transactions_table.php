<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('food_id');
            $table->unsignedBigInteger('receiver_id');
            $table->integer('quantity_received');
            $table->timestamp('pickup_time')->nullable();
            $table->integer('status')->default(0);
            $table->integer('receiver_status')->default(0);
            $table->integer('donor_status')->default(0);
            $table->timestamps();
            $table->foreign('food_id')->references('id')->on('food');
            $table->foreign('receiver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('food_transactions');
    }
};