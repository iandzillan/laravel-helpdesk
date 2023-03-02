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
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('technician_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->text('image');
            $table->text('description');
            $table->integer('status');
            $table->integer('progress');
            $table->timestamps();

            $table->foreign('urgency_id')->references('id')->on('urgencies')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('technician_id')->references('id')->on('employees')->onDelete('cascade');
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
