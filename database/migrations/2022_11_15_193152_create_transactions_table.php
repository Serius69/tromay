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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id()->unique();
            $table->double('ammount')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('photo_id')->default(1);
            $table->foreign('photo_id')->references('id')->on('photos');
            $table->unsignedBigInteger('cash_id')->default(1);
            $table->foreign('cash_id')->references('id')->on('cashs');
            $table->unsignedBigInteger('cash_id')->default(1);
            $table->foreign('cash_id')->references('id')->on('cashs');
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
