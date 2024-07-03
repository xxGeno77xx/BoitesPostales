<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonne extends Model
{
    use HasFactory;


    protected $table = "boite.abonne" ;

    protected $primaryKey  = "id_abonne" ;

}
