<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantPayments extends Model
{
    use HasFactory;

    protected $table = 'tenant_payments';

    protected $fillable = [
        'tenant_id',
        'tenant_billings_rent_id',
        'tenant_billings_electricity_id',
        'tenant_billings_water_id',
        'file_electricity',
        'file_water',
        'electricity_amount',
        'water_amount',
        'billing_month',
        'billing_year',
        'amount',
        'type',
        'get_fullname',
        'payment_type',
        'payment_proof',
        'status',
        'received_by',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenants::class, 'tenant_id');
    }

    public function billingRent()
    {
        return $this->belongsTo(TenantBillingsRent::class, 'tenant_billings_rent_id');
    }

    public function billingElectricity()
    {
        return $this->belongsTo(TenantBillingsElectricity::class, 'tenant_billings_electricity_id');
    }

    public function billingWater()
    {
        return $this->belongsTo(TenantBillingsWater::class, 'tenant_billings_water_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Admins::class, 'received_by');
    }
}
