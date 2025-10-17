<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    /**
     * Display a listing of customers for staff (read-only)
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->with(['datVe']);

        // Search by name, username, email, or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(15);

        return view('AdminLTE.staff.customers.index', compact('customers'));
    }
}
