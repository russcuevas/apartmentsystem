<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantsRentInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'room',
        'monthly_rental',
        'start_date',
    ];
}
