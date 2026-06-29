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
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('full_name')->nullable();
            $table->string('gender')->nullable();
            $table->text('bio')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_online')->default(false);
            $table->timestamp('last_seen_at')->nullable();
            $table->string('email_login_otp_hash')->nullable();
            $table->timestamp('email_login_otp_expires_at')->nullable();
            $table->boolean('completed_profile')->default(false);
            $table->rememberToken();
            $table->timestamps();

            $table->index('email_login_otp_expires_at');
            $table->index(['username', 'full_name']);
            $table->index(['is_online', 'last_seen_at']);
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
