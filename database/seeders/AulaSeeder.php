<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('aulas')->insert([
            'nombre' => 'Math Classroom',
            'diasUtilizables' => 'lunes,martes,miercoles',
            'horaApertura' => 9,
            'horaCierre' => 19,
            'capacidadPersonas' => 10,
            'duracionClase' => 2,
        ]);

        DB::table('aulas')->insert([
            'nombre' => 'Art Classroom',
            'diasUtilizables' => 'lunes,jueves,sabado',
            'horaApertura' => 8,
            'horaCierre' => 18,
            'capacidadPersonas' => 15,
            'duracionClase' => 1,
        ]);

        DB::table('aulas')->insert([
            'nombre' => 'Science Classroom',
            'diasUtilizables' => 'martes,viernes,sabado',
            'horaApertura' => 15,
            'horaCierre' => 22,
            'capacidadPersonas' => 7,
            'duracionClase' => 1,
        ]);

        DB::table('aulas')->insert([
            'nombre' => 'Geography Classroom',
            'diasUtilizables' => 'jueves,viernes',
            'horaApertura' => 8,
            'horaCierre' => 18,
            'capacidadPersonas' => 15,
            'duracionClase' => 2,
        ]);

        DB::table('aulas')->insert([
            'nombre' => 'Computer Science Classroom',
            'diasUtilizables' => 'lunes,viernes',
            'horaApertura' => 13,
            'horaCierre' => 15,
            'capacidadPersonas' => 23,
            'duracionClase' => 1,
        ]);

        DB::table('aulas')->insert([
            'nombre' => 'History Classroom',
            'diasUtilizables' => 'martes,miercoles',
            'horaApertura' => 10,
            'horaCierre' => 19,
            'capacidadPersonas' => 11,
            'duracionClase' => 3,
        ]);

    }
}
