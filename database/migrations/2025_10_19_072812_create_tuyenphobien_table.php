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
        Schema::create('tuyenphobien', function (Blueprint $table) {
            $table->id();
            $table->string('matpb', 20);
            $table->string('tentpb', 100);
            $table->string('imgtpb', 255);
            $table->integer('soluongdatdi')->default(0);
            $table->integer('soyeuthich')->default(0);
            $table->unsignedBigInteger('ma_xe');
            
            $table->foreign('ma_xe')->references('id')->on('chuyen_xe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuyenphobien');
    }
};