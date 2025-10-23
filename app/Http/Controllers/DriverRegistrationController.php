<?php

namespace App\Http\Controllers;

use App\Models\DriverApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DriverRegistrationController extends Controller
{
    public function create()
    {
        $busOwners = User::where('role', 'bus_owner')->select('id', 'name')->get();
        return Inertia::render('Driver/Register', ['busOwners' => $busOwners]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bus_owner_id' => 'required|exists:users,id',
            'license_number' => 'required|string',
            'license_image' => 'nullable|image'
        ]);

        $application = DriverApplication::create([
            'driver_id' => Auth::user()->id(),
            'bus_owner_id' => $request->bus_owner_id,
            'license_number' => $request->license_number,
            'license_image' => $request->file('license_image')?->store('licenses'),
            'status' => 'pending',
            'application_date' => now()
        ]);

        return redirect()->back()->with('success', 'Application submitted successfully');
    }

    public function index()
    {
        $applications = DriverApplication::where('driver_id', Auth::user()->id())->get();
        return Inertia::render('Driver/Applications', ['applications' => $applications]);
    }

    public function show($id)
    {
        $application = DriverApplication::where('driver_id', Auth::user()->id())->findOrFail($id);
        return Inertia::render('Driver/ApplicationDetail', ['application' => $application]);
    }
}
