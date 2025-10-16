<?php

namespace App\Http\Controllers\BusOwner;

use App\Http\Controllers\Controller;
use App\Models\NhaXe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NhaXeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        return view('AdminLTE.bus_owner.nha_xe.index', compact('busCompany'));
    }

    public function edit()
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        return view('AdminLTE.bus_owner.nha_xe.edit', compact('busCompany'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $validated = $request->validate([
            'ten_nha_xe' => 'required|string|max:255',
            'dia_chi' => 'nullable|string|max:500',
            'so_dien_thoai' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $busCompany->update($validated);

        return redirect()->route('bus-owner.nha-xe.index')
            ->with('success', 'Cập nhật thông tin nhà xe thành công!');
    }
}
