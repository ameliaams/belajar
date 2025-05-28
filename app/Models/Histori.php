<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Histori extends Model
{
    use HasFactory;

    protected $table = 'historis';

    protected $fillable = ['data_id', 'versi', 'tanggal', 'isi'];
}
