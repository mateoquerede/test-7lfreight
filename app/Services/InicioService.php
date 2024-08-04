<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Aula;
use App\Models\Reserva;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InicioService
{

    public function getViewModel(): View
    {
        $aulas = $this->getAulasArray();
        $usuarios = $this->getUsuariosArray();

        return view('inicio', [
            'aulas' => $aulas,
            'usuarios' => $usuarios
        ]);
    }

    //<editor-fold desc="getAulasArray">
    /**
     * @return array<int, array<string, int|string|array>>
     */
    private function getAulasArray(): array
    {
        $aulas = DB::select("
            SELECT
                id,
                nombre,
                diasUtilizables,
                horaApertura,
                horaCierre,
                capacidadPersonas,
                duracionClase
            FROM aulas
        ");

        // Se pasan las aulas a array
        $aulasArray = array_map(function ($aula) {
            return (array)$aula;
        }, $aulas);

        foreach ($aulasArray as &$aula) {
            $aulaId = $aula['id'];
            $diasUtilizables = explode(',', $aula['diasUtilizables']);

            $horaApertura = (int)$aula['horaApertura'];
            $horaCierre = (int)$aula['horaCierre'];
            $duracionClase = (int)$aula['duracionClase'];
            $capacidadPersonas = (int)$aula['capacidadPersonas'];

            $reservas = DB::select("
                SELECT
                    dia,
                    horaInicio,
                    horaFin
                FROM reservas
                WHERE reservas.aula_id = :id
            ", ['id' => $aulaId]);

            // Se convierte en colección para posteriormente filtrar
            $reservas = collect($reservas);

            $arrayDias = [];
            $horasPosibles = $this->calcularHorasPosibles($horaApertura, $horaCierre, $duracionClase);
            foreach ($diasUtilizables as $dia) {
                foreach ($horasPosibles as $hora) {
                    // Recuperamos las reservas para el día y hora especificados
                    $reservasFiltered = $reservas->filter(function ($reserva) use ($dia, $hora) {
                        return $reserva->dia === $dia && $reserva->horaInicio === $hora;
                    });

                    $cantidadReservas = $reservasFiltered->count();
                    $disponibilidad = $capacidadPersonas - $cantidadReservas;

                    $arrayDias[$dia][$hora] = $disponibilidad;
                }
            }

            $aula['dias'] = $arrayDias;
        }

        return $aulasArray;
    }

    /**
     * @return int[]
     */
    private function calcularHorasPosibles(int $horaApertura, int $horaCierre, int $duracion): array
    {
        $horarios = [];
        for ($i = $horaApertura; $i < $horaCierre; $i += $duracion) {
            $horarios[] = $i;
        }

        return $horarios;
    }
    //</editor-fold>

    /**
     * @return array<int, int|string>
     */
    private function getUsuariosArray(): array
    {
        $usuarios = DB::select("
            SELECT
                id,
                name
            FROM usuarios
        ");

        // Se mapean los usuarios obtenidos a array
        return array_map(function ($aula) {
            return (array)$aula;
        }, $usuarios);
    }

}
