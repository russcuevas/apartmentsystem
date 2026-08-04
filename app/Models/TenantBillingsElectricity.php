<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantBillingsElectricity extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'billing_month',
        'due_date',
        'rent_amount',
        'balance',
        'proof_of_billing',
        'status',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenants::class, 'tenant_id');
    }
}
