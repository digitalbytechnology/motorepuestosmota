<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time');
            $table->string('name', 150);
            $table->string('phone', 30);
            $table->text('observations')->nullable();
            $table->timestamps();

            // Solo index por fecha (útil para filtrar rápido)
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
