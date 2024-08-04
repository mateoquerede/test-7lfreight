<!doctype html>
<html lang="en">
<head>
    <title>Consultar reservas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">

        <div class="row">
            <div class="col">
                <h1 class="h5 my-3">Reservas del usuario: {{ $usuario->getName() }}</h1>
                <p>Estas son todas las reservas de la semana hechas por el usuario, puedes cancelar cualquiera desde el botón siempre y cuando
                a la reserva no le resten menos de 24 hs</p>
            </div>
        </div>

        <div class="row">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <td>Nombre</td>
                        <td>Día</td>
                        <td>Hora inicio</td>
                        <td>Hora fin</td>
                        <td>Operaciones</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservas as $reserva)
                        <tr>
                            <td>{{ $reserva['aula'] }}</td>
                            <td>{{ $reserva['dia'] }}</td>
                            <td>{{ $reserva['horaInicio'] }}</td>
                            <td>{{ $reserva['horaFin'] }}</td>
                            <td>
                                <form action="{{ url("cancelar-reserva/{$reserva['id']}") }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Cancelar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col">
                <a href="{{ url('/') }}" class="btn btn-primary">Volver al inicio</a>
            </div>
        </div>
    </div>
</body>
</html>
