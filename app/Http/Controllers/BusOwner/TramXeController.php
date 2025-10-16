<?php

namespace App\Http\Controllers\BusOwner;

use App\Http\Controllers\Controller;
use App\Models\TramXe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TramXeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        // Get all stations (or filter by bus company if needed)
        // Option 1: Show only stations belonging to this bus company
        // $tramXe = TramXe::where('ma_nha_xe', $busCompany->ma_nha_xe)->orderBy('ten_tram')->paginate(20);

        // Option 2: Show all stations (current implementation)
        $tramXe = TramXe::orderBy('ten_tram')->paginate(20);

        return view('AdminLTE.bus_owner.tram_xe.index', compact('tramXe', 'busCompany'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $tram = TramXe::findOrFail($id);

        return view('AdminLTE.bus_owner.tram_xe.show', compact('tram', 'busCompany'));
    }
}
