<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Halte extends Model
{
    protected $fillable = ['nama', 'lat', 'lng', 'kapasitas', 'jumlah_sekarang'];
}