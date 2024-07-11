<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BureauPoste extends Model
{
    use HasFactory;

    protected $table = 'spt.bureau_poste';

    protected $primaryKey = 'code_bureau';
}
