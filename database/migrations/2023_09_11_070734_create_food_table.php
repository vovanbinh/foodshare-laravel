<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('location_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('quantity');
            $table->datetime('expiry_date');
            $table->integer('remaining_time_to_accept')->nullable();
            $table->string('location');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('status')->default('available');
            $table->decimal('delivery_fee', 10, 2)->nullable();
            $table->string('contact_information')->nullable();
            $table->string('food_type')->nullable();
            $table->string('image_url');
            $table->string('operating_hours')->nullable();
            $table->string('payment_methods')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('location_id')->references('id')->on('location');

        });
    }
    public function down()
    {
        Schema::dropIfExists('food');
    }
};
