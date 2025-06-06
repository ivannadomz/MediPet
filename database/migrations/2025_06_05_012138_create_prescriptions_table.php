<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id(); 
            $table->text('specifications');
            
            $table->date('date');
            $table->text('reason');
            $table->text('diagnosis');
            $table->text('treatment');
            $table->string('xray_file')->nullable();   
            $table->string('lab_file')->nullable();   
            $table->timestamps();

            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
