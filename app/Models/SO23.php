<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SO23 extends Model
{
    use HasFactory;

    protected $table = 'sales_order_2023';

    protected $fillable = [
        'pid',
        'site_id_tenant',
        'site_name',
        'regional',
        'pulau',
        'area',
        'sow2',
        'kat_tower',
        'demografi',
        'tenant_existing',
        'final_status_site',
        'status_xl',
        'status_lms',
        'rfi_date',
        'aging_rfi_to_bak',
    ];
}
