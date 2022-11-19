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
        Schema::create('cashes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->double('buy')->nullable();;
            $table->double('sell')->nullable();
            $table->double('oficial')->nullable();
            $table->unsignedBigInteger('photo_id')->default(1);
            $table->foreign('photo_id')->references('id')->on('photos');
            $table->double('status')->default(1);
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
        Schema::dropIfExists('cashes');
    }
};
