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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
    
            // Add foreign key to relate 'barangay_id' column with 'barangays' table
            $table->unsignedBigInteger('barangay_id')->nullable();
            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('set null');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('user_type')->default(1)->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
