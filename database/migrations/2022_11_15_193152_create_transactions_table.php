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
            $table->unsignedBigInteger('user_id')->default(1);
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('client_id')->default(1);
            $table->foreign('client_id')->references('id')->on('clients');
            $table->unsignedBigInteger('cash1_id')->default(1);
            $table->foreign('cash1_id')->references('id')->on('cashes');
            $table->unsignedBigInteger('cash2_id')->default(1);
            $table->foreign('cash2_id')->references('id')->on('cashes');
            $table->integer('type')->default(1);
            $table->double('amount1')->nullable();
            $table->double('amount2')->nullable();
            $table->dateTime('date')->nullable();
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
