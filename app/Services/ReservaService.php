<?php

namespace App\Services;

use App\Models\Aula;
use App\Models\Reserva;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ReservaService
{

    /**
     * @param array<string, array<string, string>> $reservas
     */
    public function guardarReservas(array $reservas, int $usuarioId): JsonResponse
    {
        if (count($reservas) === 0) {
            return response()->json('No se ha enviado ningún horario válido para guardar.', 400);
        }

        $reservasNuevas = $this->mapearCamposHorarios($reservas);
        if ($this->comprobarHorariosSuperpuestos($reservasNuevas)) {
            return response()->json('La combinación de campos enviada contiene superposiciones de horarios.', 400);
        }

        $usuario = Usuario::find($usuarioId);
        if ($usuario === null) {
            return response()->json('No se ha encontrado el usuario enviado.', 404);
        }

        $reservasExistentes = $usuario->reservas;
        $reservasMapeadas = $this->mapearReservasParaComprobarSuperposicion($reservasExistentes);

        $reservasTodas = array_merge_recursive($reservasMapeadas, $reservasNuevas);
        $comprobar = $this->comprobarHorariosSuperpuestos($reservasTodas);
        if ($comprobar) {
            return response()->json('Se está intentando guardar una reserva en un horario que se superpone con una reserva existente.', 400);
        }

        foreach($reservasNuevas as $dia => $reservas) {
            foreach ($reservas as $reserva) {
                $reserva['dia'] = $dia;
                $reserva['usuario_id'] = $usuarioId;

                $reserva['aula_id'] = $reserva['aula']->getId();
                unset($reserva['aula']);

                // Este guardado con SQL sería
                // INSERT INTO reservas
                //  (horaInicio, horaFin, dia, usuario_id, aula_id)
                // VALUES (:horaInicio, :horaFin, :dia, :usuario_id, :aula_id)
                Reserva::create($reserva);
            }
        }

        return response()->json('Reservas guardadas correctamente');
    }

    //<editor-fold desc="Agregar reservas">
    private function mapearReservasParaComprobarSuperposicion(Collection $reservas): array
    {
        $mapeadas = [];

        // Me hubiera gustado resolverlo con un map, pero me sirve el foreach para agrupar por días
        foreach ($reservas as $reserva) {
            $dia = $reserva->getDia();

            $mapeadas[$dia][] = [
                'aula' => $reserva->getAula(),
                'horaInicio' => $reserva->getHoraInicio(),
                'horaFin' => $reserva->getHoraFin()
            ];
        }

        return $mapeadas;
    }

    private function comprobarHorariosSuperpuestos(array $arrayIntervalos): bool {
        // Se itera para comprobar si existe superposición en el día
        foreach($arrayIntervalos as $dia => $intervalos) {
            $comprobar = $this->comprobarHorariosDelDia($intervalos);

            if($comprobar) {
                return true;
            }
        }

        return false;
    }

    private function mapearCamposHorarios(array $camposConValores): array
    {
        $arrayIntervalos = [];

        // Primero se agrupan los valores por día y se calcula la horaFin
        foreach($camposConValores as $aulaId => $dias) {
            foreach($dias as $dia => $hora) {

                $aula = Aula::find($aulaId);
                $duracion = $aula->getDuracionClase();

                $horaFin = (int) $hora + $duracion;
                $arrayIntervalos[$dia][] = [
                    'aula'=> $aula,
                    'horaInicio' => (int) $hora,
                    'horaFin' => $horaFin
                ];
            }
        }

        return $arrayIntervalos;
    }

    private function comprobarHorariosDelDia($intervalos): bool {
        $cantidadIntervalos = count($intervalos);

        for ($i = 0; $i < $cantidadIntervalos; $i++) {
            for ($j = $i + 1; $j < $cantidadIntervalos; $j++) {
                if ($this->comprobarHorarios($intervalos[$i], $intervalos[$j])) {
                    return true;
                }
            }
        }

        return false;
    }

    private function comprobarHorarios($intervalo1, $intervalo2): bool {
        return $intervalo1['horaInicio'] < $intervalo2['horaFin'] && $intervalo1['horaFin'] > $intervalo2['horaInicio'];
    }
    //</editor-fold>

    //<editor-fold desc="Consultar reservas">
    public function getReservasMapeadas(Usuario $usuario): array
    {
        $reservas = $usuario->getReservas();

        return $reservas->map(function (Reserva $reserva) {
            return [
                'id' => $reserva->getId(),
                'aula' => $reserva->getAula()->getNombre(),
                'dia' => $reserva->getDia(),
                'horaInicio' => $reserva->getHoraInicio(),
                'horaFin' => $reserva->getHoraFin()
            ];
        })->toArray();
    }
    //</editor-fold>

}
