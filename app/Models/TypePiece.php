<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypePiece extends Model
{
    use HasFactory;


    protected $table = 'boite.type_piece';

    protected $primaryKey = 'code_type_piece';

}
