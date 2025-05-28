<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;

    protected $fillable = ['versi', 'tanggal', 'isi', 'status'];

    public function histori()
    {
        return $this->hasMany(Histori::class);
    }
}
