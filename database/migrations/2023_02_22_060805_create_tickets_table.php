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
            $table->char('ticket_number')->unique();
            $table->unsignedBigInteger('urgency_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('technician_id')->nullable();
            $table->unsignedBigInteger('sub_category_id');
            $table->text('image');
            $table->text('description');
            $table->integer('status');
            $table->integer('progress');
            $table->timestamps();

            $table->foreign('urgency_id')->references('id')->on('urgencies')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('technician_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
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
