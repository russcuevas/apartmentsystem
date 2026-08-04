<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Tenants extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'password',
        'phone_number',
        'location_id'
    ];

    public function location()
    {
        return $this->belongsTo(Locations::class, 'location_id');
    }

    public function rentInformation()
    {
        return $this->hasOne(TenantsRentInformation::class, 'tenant_id');
    }

    public function billingsRent()
    {
        return $this->hasMany(TenantBillingsRent::class, 'tenant_id');
    }

    public function billingsElectricity()
    {
        return $this->hasMany(TenantBillingsElectricity::class, 'tenant_id');
    }

    public function billingsWater()
    {
        return $this->hasMany(TenantBillingsWater::class, 'tenant_id');
    }

    public function payments()
    {
        return $this->hasMany(TenantPayments::class, 'tenant_id');
    }
}
