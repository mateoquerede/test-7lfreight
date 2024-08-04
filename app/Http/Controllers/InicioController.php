<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Reserva;
use App\Models\Usuario;
use App\Services\InicioService;
use App\Services\ReservaService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class InicioController extends Controller {

    protected InicioService $inicioService;
    protected ReservaService $reservaService;

    public function __construct(InicioService $inicioService, ReservaService $reservaService)
    {
        $this->inicioService = $inicioService;
        $this->reservaService = $reservaService;
    }

    public function inicio(): View {
        $servicio = $this->getInicioService();
        return $servicio->getViewModel();
    }

    public function agregarReservas(Request $request): JsonResponse
    {
        $usuarioId = $request->input('usuario');
        if (!isset($usuarioId)) {
            return response()->json('No se ha enviado un ID de usuario válida', 400);
        }

        $camposHora = $request->input();

        unset($camposHora['usuario']);
        unset($camposHora['_token']);

        $camposConValores = array_map(function($dias) {
            // Se filtran los días que tienen valores nulos
            return array_filter($dias, function($hora) {
                return !is_null($hora);
            });
        }, $camposHora['aulas']);


        // Se filtran las aulas sin ningún valor válido
        $reservas = array_filter($camposConValores, function($dias) {
            return !empty($dias);
        });

        $servicio = $this->getReservaService();
        return $servicio->guardarReservas($reservas, $usuarioId);
    }

    public function consultarReservas(Request $request): Response|View
    {
        $usuarioId = $request->input('usuario');
        if (!isset($usuarioId)) {
            return response('No se ha enviado un ID de usuario válida', 400);
        }

        $usuario = Usuario::find($usuarioId);
        if ($usuario === null) {
            return response('No se ha encontrado el usuario enviado.', 404);
        }

        $reservaService = $this->getReservaService();
        $reservas = $reservaService->getReservasMapeadas($usuario);

        if(count($reservas) === 0) {
            return response('El usuario recuperado todavía no tiene reservas');
        }

        return view('reservas', [
            'reservas' => $reservas,
            'usuario' => $usuario
        ]);
    }

    public function cancelarReserva(int $id)
    {
        $reserva = Reserva::find($id);
        if ($reserva === null) {
            return response('No se ha encontrado la reserva enviada.', 404);
        }

        $dia = $reserva->getDia();
        $diaEnEntero = Aula::DIAS_EN_ENTEROS[$dia];

        $horaInicio = $reserva->getHoraInicio();

        $proximaFecha = Carbon::now()
            ->next($diaEnEntero)
            ->setHour($horaInicio);

        $fechaActual = Carbon::now();
        $horasFaltantes = $fechaActual->diffInHours($proximaFecha);

        if ($horasFaltantes < 24 && $horasFaltantes > 0) {
            return response('La reserva no se puede cancelar porque restan menos de 24 horas.', 404);
        } elseif ($horasFaltantes <= 0) {
            return response('La reserva no se puede cancelar porque la fecha ya ha pasado.', 404);
        }

        // Acá dudé si eliminar la reserva o marcarla como cancelada para mantener el registro por auditoria, pero por ahora no lo veo necesario
        $reserva->delete();

        return response('Reserva cancelada correctamente');
    }

    //<editor-fold desc="Dependencias">

    public function getInicioService(): InicioService
    {
        return $this->inicioService;
    }

    public function getReservaService(): ReservaService
    {
        return $this->reservaService;
    }
    //</editor-fold>

}
