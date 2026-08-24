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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique()->index();
            $table->string('customer_name');
            $table->string('email');
            $table->string('phone_number');
            $table->text('problem_description');
            $table->string('status')->default('pending'); // pending, replied, resolved
            $table->boolean('is_opened')->default(false); // tracks if support agent has opened/viewed the ticket
            $table->timestamp('opened_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
