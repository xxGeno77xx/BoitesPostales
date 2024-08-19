<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nationalite extends Model
{
    use HasFactory;

    protected $table = 'nationalite';

    protected $primaryKey = 'code_nationalite';
}
