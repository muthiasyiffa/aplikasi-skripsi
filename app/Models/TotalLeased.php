<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalLeased extends Model
{
    use HasFactory;

    protected $table = 'total_leased';

    protected $fillable = [
        'site_id_tenant',
        'site_name',
        'regional',
        'pulau',
        'area',
        'kat_jenis_order',
        'sow2',
        'longitude',
        'latitude',
    ];
}
