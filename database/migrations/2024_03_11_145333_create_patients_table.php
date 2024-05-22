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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name',64);
            $table->string('email',64);
            $table->string('password',128);
            $table->string('phone',14);
            $table->integer('age');
            $table->string('result')->nullable();
            $table->string('mri')->nullable();
            $table->string('dr_name',64);
            $table->string('dr_gmail',64);
            $table->string('dr_phone',64);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
