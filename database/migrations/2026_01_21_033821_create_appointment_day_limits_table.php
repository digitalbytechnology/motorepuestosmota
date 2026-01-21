<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointment_day_limits', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();              // 1 límite por día
            $table->unsignedInteger('max_per_day');      // límite para ese día
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_day_limits');
    }
};
