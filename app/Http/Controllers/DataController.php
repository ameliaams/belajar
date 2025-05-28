<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
        $data = Data::orderBy('created_at', 'desc')->get();
        return view('data.index', compact('data'));
        dd($data);
    }

    public function create()
    {
        return view('data.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'isi' => 'required',
        ]);

        $newData = Data::create([
            'versi' => '1.0',
            'tanggal' => now()->toDateString(),
            'isi' => $request->isi,
            'status' => $request->status ? null : 'NON-AKTIF',
        ]);

        // Buat histori pertama
        $newData->histori()->create([
            'versi' => '1.0',
            'tanggal' => now()->toDateString(),
            'isi' => $request->isi,
        ]);

        return redirect()->route('data.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function show($id)
    {
        $data = Data::with('histori')->findOrFail($id);
        return response()->json($data);
    }

    public function edit($id)
    {
        $data = Data::findOrFail($id);
        return view('data.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = Data::findOrFail($id);
        $originalData = $data->getOriginal();

        $request->validate([
            'isi' => 'nullable|string',
            'status' => 'nullable|boolean'
        ]);

        $statusAktif = $request->has('status') ? ($request->status ? null : 'NON-AKTIF') : $originalData['status'];
        $newIsi = $request->filled('isi') ? $request->isi : $originalData['isi'];

        $isiChanged = ($originalData['isi'] != $newIsi);
        $statusChanged = ($originalData['status'] != $statusAktif);

        // Jika tidak ada perubahan sama sekali
        if (!$isiChanged && !$statusChanged) {
            return response()->json(['info' => 'Tidak ada perubahan data']);
        }

        // Tentukan versi baru
        if ($isiChanged) {
            $lastHistori = $data->histori()->latest()->first();
            $lastVersion = $lastHistori->versi ?? '1.0';
            $lastDate = $data->tanggal;

            if (now()->toDateString() == $lastDate) {
                $parts = explode('.', $lastVersion);
                $newVersion = $parts[0] . '.' . ((int)$parts[1] + 1);
            } else {
                $parts = explode('.', $lastVersion);
                $newVersion = ((int)$parts[0] + 1) . '.0';
            }
        } else {
            // Jika hanya status berubah, versi tetap sama
            $newVersion = $data->versi;
        }

        // Update data utama
        $data->update([
            'isi' => $newIsi,
            'status' => $statusAktif,
            'versi' => $newVersion,
            'tanggal' => now()->toDateString(),
        ]);

        // Buat histori konten
        if ($isiChanged && $statusChanged) {
            // $historiContent = "Update isi dan perubahan status menjadi " . ($statusAktif === null ? 'AKTIF' : 'NON-AKTIF');
            $historiContent = $newIsi;
        } elseif ($isiChanged) {
            $historiContent = $newIsi;
        } elseif ($statusChanged) {
            $historiContent = "perubahan status menjadi " . ($statusAktif === null ? 'AKTIF' : 'NON-AKTIF');
        } else {
            $historiContent = 'Perubahan data';
        }

        // Simpan histori
        $data->histori()->create([
            'versi' => $newVersion,
            'tanggal' => now()->toDateString(),
            'isi' => $historiContent,
        ]);

        return response()->json(['success' => 'Data berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $data = Data::findOrFail($id);
        $data->histori()->delete();
        $data->delete();
        return redirect()->route('data.index')->with('success', 'Data berhasil dihapus');
    }
}
