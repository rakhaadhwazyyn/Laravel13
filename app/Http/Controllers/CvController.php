<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Portofolio;

class CvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dataPortofolio'] = Portofolio::all();
        return view('admin.cv.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cv.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data['nama_lengkap'] = $request->nama_lengkap;
        $data['jabatan'] = $request->jabatan;
        $data['email'] = $request->email;
        $data['telepon'] = $request->telepon;
        $data['alamat'] = $request->alamat;
        $data['ringkasan'] = $request->ringkasan;
        $data['institusi'] = $request->institusi;
        $data['jurusan'] = $request->jurusan;
        $data['tahun_lulus'] = $request->tahun_lulus;
        $data['keahlian'] = $request->keahlian;
        $data['tampilkan_foto'] = $request->tampilkan_foto;

        // upload foto (disimpan di public/foto-cv)
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFile = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('foto-cv'), $namaFile);
            $data['foto'] = $namaFile;
        }

        Portofolio::create($data);

        return redirect()->route('cv.index')->with('success', 'Penambahan Data Berhasil!');
    }

    /**
     * Display the specified resource. (hasil akhir CV)
     */
    public function show(string $id)
    {
        $data['cv'] = Portofolio::findOrFail($id);
        return view('admin.cv.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['dataPortofolio'] = Portofolio::findOrFail($id);
        return view('admin.cv.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $portofolio = Portofolio::findOrFail($id);

        $portofolio->nama_lengkap = $request->nama_lengkap;
        $portofolio->jabatan = $request->jabatan;
        $portofolio->email = $request->email;
        $portofolio->telepon = $request->telepon;
        $portofolio->alamat = $request->alamat;
        $portofolio->ringkasan = $request->ringkasan;
        $portofolio->institusi = $request->institusi;
        $portofolio->jurusan = $request->jurusan;
        $portofolio->tahun_lulus = $request->tahun_lulus;
        $portofolio->keahlian = $request->keahlian;
        $portofolio->tampilkan_foto = $request->tampilkan_foto;

        // ganti foto hanya jika ada file baru yang diupload
        if ($request->hasFile('foto')) {
            if ($portofolio->foto && file_exists(public_path('foto-cv/' . $portofolio->foto))) {
                unlink(public_path('foto-cv/' . $portofolio->foto));
            }
            $file = $request->file('foto');
            $namaFile = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('foto-cv'), $namaFile);
            $portofolio->foto = $namaFile;
        }

        $portofolio->save();
        return redirect()->route('cv.index')->with('success', 'Perubahan Data Berhasil!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $portofolio = Portofolio::findOrFail($id);

        if ($portofolio->foto && file_exists(public_path('foto-cv/' . $portofolio->foto))) {
            unlink(public_path('foto-cv/' . $portofolio->foto));
        }

        $portofolio->delete();
        return redirect()->route('cv.index')->with('success', 'data berhasil dihapus');
    }
}
