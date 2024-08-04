<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            'name' => 'Alumno 1',
            'email' => 'alumno1@email.com',
            'password' => '12345',
        ]);

        DB::table('usuarios')->insert([
            'name' => 'Alumno 2',
            'email' => 'alumno2@email.com',
            'password' => '12345',
        ]);

        DB::table('usuarios')->insert([
            'name' => 'Alumno 3',
            'email' => 'alumno3@email.com',
            'password' => '12345',
        ]);

    }
}
