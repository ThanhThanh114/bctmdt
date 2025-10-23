<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::where('created_by', Auth::user()->id())->with('stations')->get();
        return Inertia::render('Admin/Routes', ['routes' => $routes]);
    }

    public function create()
    {
        $stations = Station::all();
        return Inertia::render('Admin/RouteCreate', ['stations' => $stations]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'origin' => 'required|string',
            'destination' => 'required|string',
            'distance' => 'required|numeric',
            'duration_minutes' => 'required|integer',
            'stations' => 'required|array'
        ]);

        $route = Route::create([
            'name' => $request->name,
            'origin' => $request->origin,
            'destination' => $request->destination,
            'distance' => $request->distance,
            'duration_minutes' => $request->duration_minutes,
            'created_by' => Auth::user()->id()
        ]);

        $route->stations()->attach($request->stations);

        return redirect()->route('routes.index')->with('success', 'Route created successfully');
    }

    public function show($id)
    {
        $route = Route::with('stations')->findOrFail($id);
        return Inertia::render('User/RouteMap', ['route' => $route]);
    }
}
