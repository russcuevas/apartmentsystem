<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Locations;
use Illuminate\Http\Request;

class LocationsController extends Controller
{
    /**
     * Display the specified location details page.
     */
    public function LocationsPage($id)
    {
        $location = Locations::findOrFail($id);
        return view('admins.locations.index', compact('location'));
    }
}
