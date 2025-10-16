<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        // Lọc theo chi nhánh
        if ($request->filled('branch') && $request->branch !== 'all') {
            $query->where('branch', $request->branch);
        }

        $contacts = $query->orderBy('created_at', 'desc')->paginate(15);

        // Thống kê
        $stats = [
            'total' => Contact::count(),
            'today' => Contact::whereDate('created_at', today())->count(),
            'this_week' => Contact::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Contact::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count(),
        ];

        return view('AdminLTE.admin.contact.index', compact('contacts', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        return view('AdminLTE.admin.contact.show', compact('contact'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        try {
            $contact->delete();
            return redirect()->route('admin.contact.index')
                ->with('success', 'Xóa liên hệ thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.contact.index')
                ->with('error', 'Không thể xóa liên hệ này!');
        }
    }

    /**
     * Bulk delete contacts
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'contact_ids' => 'required|array',
            'contact_ids.*' => 'exists:contact,id',
        ]);

        Contact::whereIn('id', $validated['contact_ids'])->delete();

        return redirect()->back()->with('success', 'Đã xóa ' . count($validated['contact_ids']) . ' liên hệ!');
    }

    /**
     * Export contacts
     */
    public function export(Request $request)
    {
        // TODO: Implement export functionality
        return redirect()->back()->with('info', 'Chức năng xuất dữ liệu đang được phát triển!');
    }
}
