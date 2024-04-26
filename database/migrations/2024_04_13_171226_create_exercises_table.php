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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name',128);
            $table->string('link',128);
            $table->string('img',128);
            $table->json('instructions');
            $table->enum('phase',[1,2,3])->nullable();
            $table->enum('type',['Primary','Optional','Alternate'])->default('Primary');
            $table->unsignedBigInteger('protocol_id');
            $table->foreign('protocol_id')->references('id')->on('protocols')->onUpdate('cascade')->onDelete('cascade');
           
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
