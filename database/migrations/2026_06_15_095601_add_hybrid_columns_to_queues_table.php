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
        Schema::table('queues', function (Blueprint $table) {
            $table->string('queue_source')->default('walk_in');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('last_email_sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            $table->dropColumn(['queue_source', 'checked_in_at', 'last_email_sent_at']);
        });
    }
};
