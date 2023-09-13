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
        Schema::create('immunization_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('infant_id');
            $table->unsignedBigInteger('barangay_id');
            $table->unsignedBigInteger('vaccine_id');
            $table->integer('dose_number');
            $table->date('immunization_date');
            $table->string('administered_by');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('infant_id')->references('id')->on('infants');
            $table->foreign('barangay_id')->references('id')->on('barangays');
            $table->foreign('vaccine_id')->references('id')->on('vaccines');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('immunization_records');
    }
};
