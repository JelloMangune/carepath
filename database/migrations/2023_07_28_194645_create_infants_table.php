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
        Schema::create('infants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tracking_number', 10); // Added tracking_number column
            $table->enum('sex', ['Male', 'Female', 'Other']);
            $table->date('birth_date');
            $table->string('family_serial_number')->nullable();
            $table->unsignedBigInteger('barangay_id')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('length', 5, 2)->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('complete_address')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            $table->foreign('barangay_id')->references('id')->on('barangays');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infants');
    }
};
