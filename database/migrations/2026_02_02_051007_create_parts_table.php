<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->id();

            $table->string('sku', 50)->nullable()->unique();
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();

            // Texto libre: a qué motos le queda (para búsqueda)
            $table->text('aplica_a')->nullable(); // ej: "XR150, GN125, AX100"

            $table->foreignId('category_id')->nullable()
                ->constrained('part_categories')->nullOnDelete();

            $table->string('marca', 80)->nullable(); // marca del repuesto

            $table->decimal('costo', 10, 2)->default(0);
            $table->decimal('precio', 10, 2)->default(0);

            $table->integer('stock')->default(0);

            // Stock mínimo
            $table->integer('stock_min')->default(1);

            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};
