<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriePro extends Model
{
    use HasFactory;

    protected $table = 'boite.categorie_professionnelle';

    protected $primaryKey = 'code_categ_prof';

}
