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

            $table->unsignedBigInteger('quotation_id')->default(1);
            $table->foreign('quotation_id')->references('id')->on('quotations');

            $table->double('ammount')->nullable();
            $table->date('date')->nullable();

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
