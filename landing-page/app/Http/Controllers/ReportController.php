<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::latest()->get();
        return view('admin.reports.index', compact('reports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelapor' => 'required|min:3',
            'lokasi' => 'required',
            'deskripsi' => 'required|min:10',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/reports'), $filename);
            $data['foto'] = $filename;
        }

        Report::create($data);

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil ditambahkan!');
    }

    public function update(Request $request, Report $report)
    {
        $request->validate([
            'nama_pelapor' => 'required|min:3',
            'lokasi' => 'required',
            'deskripsi' => 'required|min:10',
            'status' => 'required'
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            if ($report->foto && file_exists(public_path('uploads/reports/' . $report->foto))) {
                unlink(public_path('uploads/reports/' . $report->foto));
            }
            $file = $request->file('foto');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/reports'), $filename);
            $data['foto'] = $filename;
        }

        $report->update($data);

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil diupdate!');
    }

    public function destroy(Report $report)
    {
        if ($report->foto && file_exists(public_path('uploads/reports/' . $report->foto))) {
            unlink(public_path('uploads/reports/' . $report->foto));
        }
        $report->delete();
        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dihapus!');
    }
}