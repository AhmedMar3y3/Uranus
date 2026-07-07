<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (! Schema::hasColumn('messages', 'ciphertext')) {
                $table->longText('ciphertext')->nullable()->after('body');
            }

            if (! Schema::hasColumn('messages', 'nonce')) {
                $table->text('nonce')->nullable()->after('ciphertext');
            }

            if (! Schema::hasColumn('messages', 'key_id')) {
                $table->string('key_id')->nullable()->after('nonce');
            }

            if (! Schema::hasColumn('messages', 'encryption_version')) {
                $table->string('encryption_version', 50)->nullable()->after('key_id');
            }

            $table->index(['conversation_id', 'key_id'], 'messages_conversation_key_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('messages_conversation_key_id_index');

            foreach (['ciphertext', 'nonce', 'key_id', 'encryption_version'] as $column) {
                if (Schema::hasColumn('messages', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
