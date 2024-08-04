<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aula extends Model
{
    const DIAS = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];

    const DIAS_EN_ENTEROS = [
      'lunes' => 1,
      'martes' => 2,
      'miercoles' => 3,
      'jueves' => 4,
      'viernes' => 5,
      'sabado' => 6,
    ];

    use HasFactory;

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    public string $nombre;

    /**
     * Array de días que el aula es utilizable
     *
     * @var string[]
     */
    public array $diasUtilizables;

    public int $horaApertura;

    public int $horaCierre;

    public int $capacidadPersonas;

    /**
     * Duración de la clase en horas
     */
    public int $duracionClase;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'diasUtilizables',
        'horaApertura',
        'horaCierre',
        'capacidadPersonas',
        'duracionClase'
    ];

    //<editor-fold desc="Getters">
    public function getId(): int
    {
        return $this->attributes['id'];
    }

    public function getNombre(): string
    {
        return $this->attributes['nombre'];
    }

    public function getDiasUtilizables(): array
    {
        return $this->attributes['diasUtilizables'];
    }

    public function getHoraApertura(): int
    {
        return $this->attributes['horaApertura'];
    }

    public function getHoraCierre(): int
    {
        return $this->attributes['horaCierre'];
    }

    public function getCapacidadPersonas(): int
    {
        return $this->attributes['capacidadPersonas'];
    }

    public function getDuracionClase(): int
    {
        return $this->attributes['duracionClase'];
    }
    //</editor-fold>

}
