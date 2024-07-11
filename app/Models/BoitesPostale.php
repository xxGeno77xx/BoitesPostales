<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoitesPostale extends Model
{
    use HasFactory;

    protected $table = 'boite.boite_postale';

    protected $primaryKey = 'id_bp';

    public $timestamps = false;

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(Contrat::class, 'id_bp', 'id_bp');
    }

    public function abonne(): HasMany
    {

        return $this->hasmany(Abonne::class, 'id_abonne', 'id_bp');
    }
}
