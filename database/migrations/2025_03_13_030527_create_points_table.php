<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('points', function (Blueprint $table) {
            $table->id();
            $table->geometry('geom');
            $table->string('name'); //shft+alt+bawah
            $table->text('description'); //agar bisa lebih dari 255 beda dengan string
            $table->timestamps();
        });
    } //method

    /**
     * Reverse the migrations.
     **/
    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};
