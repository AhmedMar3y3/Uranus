<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $needsIndexes = ! Schema::hasColumn('users', 'username');

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->unique()->after('password');
            }

            if (! Schema::hasColumn('users', 'full_name')) {
                $table->string('full_name')->nullable()->after('username');
            }

            if (! Schema::hasColumn('users', 'gender')) {
                $table->string('gender')->nullable()->after('full_name');
            }

            if (! Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('gender');
            }

            if (! Schema::hasColumn('users', 'image_path')) {
                $table->string('image_path')->nullable()->after('bio');
            }

            if (! Schema::hasColumn('users', 'is_online')) {
                $table->boolean('is_online')->default(false)->after('image_path');
            }

            if (! Schema::hasColumn('users', 'last_seen_at')) {
                $table->timestamp('last_seen_at')->nullable()->after('is_online');
            }

            if (! Schema::hasColumn('users', 'email_login_otp_hash')) {
                $table->string('email_login_otp_hash')->nullable()->after('last_seen_at');
            }

            if (! Schema::hasColumn('users', 'email_login_otp_expires_at')) {
                $table->timestamp('email_login_otp_expires_at')->nullable()->after('email_login_otp_hash');
            }

            if (! Schema::hasColumn('users', 'completed_profile')) {
                $table->boolean('completed_profile')->default(false)->after('email_login_otp_expires_at');
            }
        });

        if ($needsIndexes) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('email_login_otp_expires_at', 'users_email_login_otp_expires_at_index');
                $table->index(['username', 'full_name'], 'users_username_full_name_index');
                $table->index(['is_online', 'last_seen_at'], 'users_is_online_last_seen_at_index');
            });
        }
    }

    public function down(): void
    {
        //
    }
};
