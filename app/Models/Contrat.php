<?php

namespace App\Models;

use App\Models\BoitesPostale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contrat extends Model
{
    use HasFactory;

    protected $table = "boite.contrat" ;

    protected $primaryKey  = "ref_contrat" ;

    // public function boitePostale(): HasOne
    // {
    //     return $this->hasOne(BoitesPostale::class, "id_bp", "id_bp");
    // }


}

