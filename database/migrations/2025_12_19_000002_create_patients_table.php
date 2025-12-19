<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('medical_record_no')->index();
            $table->string('nationality')->nullable();
            $table->string('room_no')->nullable();
            $table->string('bed_no')->nullable();
            $table->text('diagnosis')->nullable();
            $table->string('specialty')->nullable();
            $table->boolean('has_companion')->default(false);
            $table->date('admission_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};