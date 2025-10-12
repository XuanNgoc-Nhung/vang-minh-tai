<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CauHinh;

class CauHinhController extends Controller
{
    public function cauHinh()
    {
        $cauHinh = CauHinh::query()->find(1);
        return view('admin.cau-hinh', compact('cauHinh'));
    }

    public function storeCauHinh(Request $request)
    {
        $data = $request->validate([
            'token_tele' => ['nullable','string','max:255'],
            'id_tele' => ['nullable','string','max:255'],
            'id_live_chat' => ['nullable','string','max:255'],
            'link_facebook' => ['nullable','string','max:255'],
            'ma_so_doanh_nghiep' => ['nullable','string','max:255'],
        ]);

        // Single-row table: always write to id = 1
        // Use updateOrCreate to enforce id=1
        CauHinh::query()->updateOrCreate(['id' => 1], $data);

        return redirect()->route('admin.cau-hinh')->with('success', 'Đã lưu cấu hình hệ thống.');
    }

    public function updateCauHinh(Request $request)
    {
        // Delegate to store for idempotent upsert behavior
        return $this->storeCauHinh($request);
    }

    public function destroyCauHinh(Request $request)
    {
        // Optional: clear settings if needed (id = 1 only)
        $record = CauHinh::query()->find(1);
        if ($record) {
            $record->delete();
        }
        return redirect()->route('admin.cau-hinh')->with('success', 'Đã xoá cấu hình.');
    }
}
