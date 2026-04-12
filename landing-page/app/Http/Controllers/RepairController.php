<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\Report;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function index()
    {
        $repairs = Repair::with('report')->latest()->get();
        $reports = Report::where('status', '!=', 'selesai')->get();
        return view('admin.repairs.index', compact('repairs', 'reports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'report_id' => 'required|exists:reports,id',
            'nama_petugas' => 'required|min:3',
            'tanggal_mulai' => 'required|date',
            'progress' => 'required|integer|min:0|max:100',
            'status' => 'required'
        ]);

        Repair::create($request->all());

        $report = Report::find($request->report_id);
        $report->update(['status' => 'diproses']);

        return redirect()->route('repairs.index')->with('success', 'Perbaikan berhasil ditambahkan!');
    }

    public function update(Request $request, Repair $repair)
    {
        $request->validate([
            'nama_petugas' => 'required|min:3',
            'tanggal_mulai' => 'required|date',
            'progress' => 'required|integer|min:0|max:100',
            'status' => 'required'
        ]);

        $repair->update($request->all());

        $report = Report::find($repair->report_id);
        if ($request->progress == 100 || $request->status == 'selesai') {
            $report->update(['status' => 'selesai']);
        } else {
            $report->update(['status' => 'diproses']);
        }

        return redirect()->route('repairs.index')->with('success', 'Perbaikan berhasil diupdate!');
    }

    public function destroy(Repair $repair)
    {
        $repair->delete();
        return redirect()->route('repairs.index')->with('success', 'Perbaikan berhasil dihapus!');
    }
}