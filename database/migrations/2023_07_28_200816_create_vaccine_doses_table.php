<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('vaccine_doses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vaccine_id');
            $table->integer('dose_number');
            $table->decimal('months_to_take', 5, 2);
            $table->timestamps();
    
            $table->foreign('vaccine_id')->references('id')->on('vaccines');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccine_doses');
    }
};
