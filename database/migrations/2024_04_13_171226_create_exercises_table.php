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
            $table->string('link',128);
            $table->string('description',128);
            $table->enum('status',[0,1,2,3])->default(0)->comment("0=>No , 1=>Primary , 2=>Optional , 3=>Alternate ");
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
