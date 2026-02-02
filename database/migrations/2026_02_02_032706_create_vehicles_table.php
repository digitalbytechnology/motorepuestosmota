<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            // Identificación
            $table->string('chasis', 60)->nullable()->index();
            $table->string('placa', 20)->nullable()->unique();

            // Clasificación
            $table->string('uso', 50)->nullable();          // particular, comercial, etc.
            $table->string('tipo', 50)->nullable();         // moto, carro, pickup, etc.
            $table->string('accionado', 50)->nullable();    // 4x2, 4x4, cadena, etc.

            // Datos generales
            $table->string('marca', 60)->nullable()->index();
            $table->string('modelo', 60)->nullable()->index(); // puede ser año o nombre
            $table->string('linea', 60)->nullable();           // variante/linea

            // Números
            $table->string('no_serie', 80)->nullable()->index();
            $table->string('no_motor', 80)->nullable()->index();

            // Características
            $table->string('color', 40)->nullable();
            $table->unsignedSmallInteger('cilindrada')->nullable(); // cc
            $table->unsignedTinyInteger('cilindros')->nullable();
            $table->decimal('tonelaje', 6, 2)->nullable();          // ej: 1.50
            $table->unsignedTinyInteger('asientos')->nullable();
            $table->unsignedTinyInteger('ejes')->nullable();
            $table->unsignedTinyInteger('puertas')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
