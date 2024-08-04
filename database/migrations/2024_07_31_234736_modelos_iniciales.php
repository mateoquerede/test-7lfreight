<?php

declare(strict_types=1);

use App\Models\Aula;
use App\Models\Usuario;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->set('diasUtilizables', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado']);
            $table->integer('horaApertura');
            $table->integer('horaCierre');
            $table->integer('capacidadPersonas');
            $table->integer('duracionClase');
        });

        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Aula::class);
            $table->foreignIdFor(Usuario::class);
            $table->enum('dia', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado']);
            $table->integer('horaInicio');
            $table->integer('horaFin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('aulas');
        Schema::drop('reservas');
    }
};
