<?php

use App\Models\Aula;

$dias = Aula::DIAS;

?>

<!doctype html>
<html lang="en">
<head>
    <title>Inicio</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">

        <div class="row">
            <div class="col">
                <h1 class="h5 my-3">Disponibilidad de aulas</h1>
                <p>Selecciona las clases que quieres reservar según los días disponibles en la tabla,
                    intenta que no se superpongan los horarios, en caso de suceder el sistema avisará para que lo modifiques.</p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <form method="POST" action="{{ url('agregar-reservas') }}">
                @csrf

                <table class="table table-bordered">
                    <thead class="table-light">
                    <tr>
                        <td>Aula</td>
                        <td>Duración</td>
                        @foreach ($dias as $dia)
                            <td>{{ ucfirst($dia) }}</td>
                        @endforeach
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($aulas as $aula)
                        <tr>
                            <td>{{ $aula['nombre'] }}</td>
                            <td>{{ $aula['duracionClase'] }} horas</td>
                            @foreach ($dias as $dia)
                                    <?php $aulaDias = $aula['dias'] ?>

                                @if(isset($aulaDias[$dia]))
                                    <td>
                                        <label for="{{$aula['id']}}-{{$dia}}">Disponibilidad</label>
                                        <select name="aulas[{{$aula['id']}}][{{$dia}}]" class="form-control">
                                            <option value="">Sin seleccionar</option>
                                            @foreach($aulaDias[$dia] as $hora => $disponibilidad)
                                                <option value="{{ $hora }}" {{ $disponibilidad === 0 ? 'disabled' : '' }}>
                                                    {{ $hora }}hs ({{$disponibilidad}} lugares)
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col d-flex justify-content-between">
                <div>
                    <label for="usuarios">Seleccionar usuario</label>
                    <select name="usuario" class="form-control">
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario['id'] }}">{{ $usuario['name'] }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-success mt-3">Reservar clases</button>
                </div>
            </form>

            <form method="GET" action="{{ url('consultar-reservas') }}">
                <div>
                    <label for="usuarios">Seleccionar usuario</label>
                    <select name="usuario" class="form-control">
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario['id'] }}">{{ $usuario['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-primary mt-3">Consultar reservas hechas</button>
            </form>

            </div>
        </div>
    </div>
</body>
</html>
