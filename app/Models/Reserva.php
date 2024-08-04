<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Reserva extends Model
{
    use HasFactory, Notifiable;

    public $timestamps = false;

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    public function aula(): BelongsTo
    {
        return $this->belongsTo(Aula::class);
    }

    public string $dia;

    public int $horaInicio;

    public int $horaFin;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'aula_id',
        'usuario_id',
        'dia',
        'horaInicio',
        'horaFin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getAula(): Aula
    {
        return $this->aula;
    }

    public function getId(): string
    {
        return $this->attributes['id'];
    }

    public function getDia(): string
    {
        return $this->attributes['dia'];
    }

    public function getHoraInicio(): int
    {
        return $this->attributes['horaInicio'];
    }

    public function getHoraFin(): int
    {
        return $this->attributes['horaFin'];
    }

}
