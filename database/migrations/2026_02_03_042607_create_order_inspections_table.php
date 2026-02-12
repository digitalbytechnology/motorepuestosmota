<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('order_inspections', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_id')->constrained()->cascadeOnDelete();

      // Checklist funcionamiento (true/false/null = N/A)
      $table->boolean('luz_silvin_bajas')->nullable();
      $table->boolean('luz_silvin_altas')->nullable();
      $table->boolean('luz_stop')->nullable();
      $table->boolean('pidevias')->nullable();
      $table->boolean('bocina')->nullable();
      $table->boolean('neblineras')->nullable();
      $table->boolean('alarma')->nullable();

      // Niveles / estado
      $table->unsignedTinyInteger('aceite_pct')->nullable(); // 0,25,50,75,100
      $table->string('gasolina_level', 10)->nullable(); // E, Q1, H, Q3, F
      $table->boolean('arranca')->nullable();

      // Testigos
      $table->boolean('check_engine')->nullable();
      $table->string('check_engine_detalle', 120)->nullable();

      // Opcionales
      $table->unsignedInteger('odometro')->nullable(); // km

      // Extras recomendados
      $table->enum('frenos', ['ok','regular','malo'])->nullable();
      $table->enum('llantas', ['ok','gastadas'])->nullable();
      $table->string('llantas_profundidad', 30)->nullable(); // opcional texto "3mm" etc
      $table->boolean('espejos')->nullable(); // true/false/null

      // Documentos / accesorios (texto libre y checklist simple)
      $table->boolean('doc_tarjeta')->nullable();
      $table->boolean('doc_copia_llave')->nullable();
      $table->text('accesorios_recibidos')->nullable();

      // Observaciones / problemas
      $table->text('observaciones')->nullable();

      // Firma digital (guardamos imagen base64 o archivo; aquí guardamos path)
      $table->string('firma_path')->nullable();

      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamps();

      $table->unique('order_id'); // 1 inspección por orden (puedes quitarlo si quieres histórico)
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('order_inspections');
  }
};
