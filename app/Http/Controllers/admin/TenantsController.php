<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Tenants;
use App\Models\TenantsRentInformation;
use App\Models\Locations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TenantsController extends Controller
{
    /**
     * Display the Tenants listing with optional location filtering.
     */
    public function TenantPage(Request $request)
    {
        $locationId = $request->get('location_id');

        $query = Tenants::with(['location', 'rentInformation']);
        if ($locationId) {
            $query->where('location_id', $locationId);
        }

        $tenants = $query->latest()->get();
        $locations = Locations::all();
        $selectedLocation = $locationId ? Locations::find($locationId) : null;

        return view('admins.locations.tenants.index', compact('tenants', 'locations', 'selectedLocation', 'locationId'));
    }

    /**
     * Store a newly created Tenant and their Rent Information.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname'       => 'required|string|max:255',
            'phone_number'   => 'required|string|max:20|unique:tenants,phone_number',
            'password'       => 'required|string|min:6',
            'location_id'    => 'required|exists:locations,id',
            'room'           => 'required|string|max:255',
            'monthly_rental' => 'required|numeric|min:0',
            'start_date'     => 'required|date',
        ]);

        DB::transaction(function () use ($validated) {
            $tenant = Tenants::create([
                'fullname'     => $validated['fullname'],
                'phone_number' => $validated['phone_number'],
                'password'     => Hash::make($validated['password']),
                'location_id'  => $validated['location_id'],
            ]);

            TenantsRentInformation::create([
                'tenant_id'      => $tenant->id,
                'room'           => $validated['room'],
                'monthly_rental' => $validated['monthly_rental'],
                'start_date'     => $validated['start_date'],
            ]);
        });

        return redirect()->back()->with('success', 'Tenant and rent information created successfully!');
    }
}

