<?php

namespace App\Http\Controllers\BusOwner;

use App\Http\Controllers\Controller;
use App\Models\TinTuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TinTucController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $tinTuc = TinTuc::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('AdminLTE.bus_owner.tin_tuc.index', compact('tinTuc', 'busCompany'));
    }

    public function create()
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        return view('AdminLTE.bus_owner.tin_tuc.create', compact('busCompany'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string',
            'hinh_anh' => 'nullable|image|max:2048',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('hinh_anh')) {
            $path = $request->file('hinh_anh')->store('tin_tuc', 'public');
            $validated['hinh_anh'] = $path;
        }

        TinTuc::create($validated);

        return redirect()->route('bus-owner.tin-tuc.index')
            ->with('success', 'Thêm tin tức thành công!');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $busCompany = $user->nhaXe;

        if (!$busCompany) {
            return redirect()->route('bus-owner.dashboard')
                ->with('warning', 'Bạn chưa được gán cho nhà xe nào.');
        }

        $tinTuc = TinTuc::where('user_id', $user->id)->findOrFail($id);

        return view('AdminLTE.bus_owner.tin_tuc.edit', compact('tinTuc', 'busCompany'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $tinTuc = TinTuc::where('user_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string',
            'hinh_anh' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('hinh_anh')) {
            $path = $request->file('hinh_anh')->store('tin_tuc', 'public');
            $validated['hinh_anh'] = $path;
        }

        $tinTuc->update($validated);

        return redirect()->route('bus-owner.tin-tuc.index')
            ->with('success', 'Cập nhật tin tức thành công!');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $tinTuc = TinTuc::where('user_id', $user->id)->findOrFail($id);
        $tinTuc->delete();

        return redirect()->route('bus-owner.tin-tuc.index')
            ->with('success', 'Xóa tin tức thành công!');
    }
}
