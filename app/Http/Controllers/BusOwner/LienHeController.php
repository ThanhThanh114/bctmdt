<?php

namespace App\Http\Controllers\BusOwner;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LienHeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $query = Contact::orderBy('created_at', 'desc');

        // Filter by branch if needed
        if ($request->has('branch') && $request->branch != '') {
            $query->where('branch', $request->branch);
        }

        $lienHe = $query->paginate(20);

        $stats = [
            'total' => Contact::count(),
            'today' => Contact::whereDate('created_at', today())->count(),
            'this_month' => Contact::whereMonth('created_at', now()->month)->count(),
        ];

        return view('AdminLTE.bus_owner.lien_he.index', compact('lienHe', 'busCompany', 'stats'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $lienHe = Contact::findOrFail($id);

        // Mark as read if not read yet
        if ($lienHe->trang_thai == 'Chưa xử lý') {
            $lienHe->update(['trang_thai' => 'Đã xem']);
        }

        return view('AdminLTE.bus_owner.lien_he.show', compact('lienHe', 'busCompany'));
    }

    public function updateStatus(Request $request, $id)
    {
        $lienHe = Contact::findOrFail($id);

        $validated = $request->validate([
            'trang_thai' => 'required|in:Chưa xử lý,Đã xem,Đã xử lý',
            'ghi_chu' => 'nullable|string',
        ]);

        $lienHe->update($validated);

        return redirect()->route('bus-owner.lien-he.show', $id)
            ->with('success', 'Cập nhật trạng thái thành công!');
    }
}
