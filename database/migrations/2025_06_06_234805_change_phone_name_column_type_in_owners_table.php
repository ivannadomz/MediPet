<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            $table->dropColumn('owner_name'); 
            $table->string('phone')->change(); 
        });
    }

    public function down(): void
    {
        Schema::table('owners', function (Blueprint $table) {
            $table->text('owner_name'); 
            $table->integer('phone')->change(); 
        });
    }
};
