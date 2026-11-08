<?php

namespace App\Models;

use Database\Factories\FacultadFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Catálogo de facultades. Una facultad tiene muchas carreras. */
#[Fillable(['nombre'])]
class Facultad extends Model
{
    /** @use HasFactory<FacultadFactory> */
    use HasFactory;

    protected $table = 'facultades';

    /** @return HasMany<Carrera, $this> */
    public function carreras(): HasMany
    {
        return $this->hasMany(Carrera::class);
    }
}
