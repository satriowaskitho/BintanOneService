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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained('visitors')->cascadeOnDelete();
            $table->foreignId('service_type_id')->constrained('service_types')->cascadeOnDelete();
            $table->date('date');
            $table->time('time');
            $table->text('purpose');
            $table->text('required_documents'); // Stores document names as comma-separated or json string
            $table->string('email');
            $table->string('status')->default('scheduled'); // scheduled, checked_in, completed, cancelled
            $table->string('token')->unique();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('last_email_sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
