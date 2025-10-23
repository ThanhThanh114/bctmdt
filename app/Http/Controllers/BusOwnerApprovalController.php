<?php

namespace App\Http\Controllers;

use App\Models\DriverApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BusOwnerApprovalController extends Controller
{
    public function index()
    {
        $applications = DriverApplication::where('bus_owner_id', Auth::user()->id())
            ->with('driver')
            ->get();
        return Inertia::render('BusOwner/Approvals', ['applications' => $applications]);
    }

    public function approve(Request $request, $id)
    {
        $application = DriverApplication::findOrFail($id);
        $application->update([
            'status' => 'approved',
            'approval_date' => now()
        ]);

        $application->driver->update(['role' => 'driver']);

        return redirect()->back()->with('success', 'Driver approved successfully');
    }

    public function reject(Request $request, $id)
    {
        $application = DriverApplication::findOrFail($id);
        $application->update([
            'status' => 'rejected',
            'notes' => $request->notes,
            'approval_date' => now()
        ]);

        return redirect()->back()->with('success', 'Driver rejected');
    }

    public function show($id)
    {
        $application = DriverApplication::where('bus_owner_id', Auth::user()->id())->with('driver')->findOrFail($id);
        return Inertia::render('BusOwner/ApplicationDetail', ['application' => $application]);
    }
}
