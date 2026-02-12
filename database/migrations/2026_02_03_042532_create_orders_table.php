<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('orders', function (Blueprint $table) {
      $table->id();

      $table->foreignId('client_id')->nullable()
        ->constrained('clients')->nullOnDelete();

      $table->foreignId('vehicle_id')->nullable()
        ->constrained('vehicles')->nullOnDelete();

      $table->enum('status', ['abierta','proceso','finalizada','entregada'])
        ->default('abierta');

      $table->text('notes')->nullable();

      // totales (los llenaremos cuando metamos mano de obra y repuestos)
      $table->decimal('labor_total', 10, 2)->default(0);
      $table->decimal('parts_total', 10, 2)->default(0);
      $table->decimal('grand_total', 10, 2)->default(0);

      $table->foreignId('created_by')->nullable()
        ->constrained('users')->nullOnDelete();

      $table->timestamps();

      $table->index(['status','created_at']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};
