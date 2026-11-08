<?php

namespace App\Models;

use Database\Factories\CarreraFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Catálogo de carreras. Cada carrera pertenece a una facultad y tiene muchos estudiantes. */
#[Fillable(['facultad_id', 'nombre'])]
class Carrera extends Model
{
    /** @use HasFactory<CarreraFactory> */
    use HasFactory;

    /** @return BelongsTo<Facultad, $this> */
    public function facultad(): BelongsTo
    {
        return $this->belongsTo(Facultad::class);
    }

    /** @return HasMany<Estudiante, $this> */
    public function estudiantes(): HasMany
    {
        return $this->hasMany(Estudiante::class);
    }
}
