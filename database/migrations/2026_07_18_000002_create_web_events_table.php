<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('web_events', function (Blueprint $table) {
            $table->id();
            $table->string('type', 32);
            $table->char('visitor_hash', 64);
            $table->foreignId('apk_release_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('occurred_at')->useCurrent();

            $table->index(['type', 'occurred_at']);
            $table->index(['visitor_hash', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_events');
    }
};
