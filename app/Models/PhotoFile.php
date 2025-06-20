<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoFile extends Model
{
    use HasFactory;

    protected $fillable = ['photo_id', 'file_path'];

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }
}
