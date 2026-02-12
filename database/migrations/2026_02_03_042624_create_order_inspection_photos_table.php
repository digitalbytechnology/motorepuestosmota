<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('order_inspection_photos', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_inspection_id')->constrained()->cascadeOnDelete();

      $table->string('path');                 // foto original
      $table->json('annotations')->nullable(); // marcas (JSON)
      $table->string('notes', 150)->nullable();

      // opcional PRO: imagen aplanada para imprimir
      $table->string('flattened_path')->nullable();

      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('order_inspection_photos');
  }
};
