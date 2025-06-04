<?php

namespace App\Http\Controllers;

use App\Models\Angkutan;
use App\Models\Merek;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class AngkutanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Pastikan baris ini mengambil data dari database Anda.
        // Jika tabel 'angkutans' kosong, maka tidak ada data yang akan muncul.
        // Gunakan paginate() untuk data yang lebih besar.
        $angkutans = Angkutan::with(['perusahaan', 'merek'])->paginate(10); // Contoh: Mengambil 10 data angkutan per halaman

        // Pastikan nama key 'angkutans' sesuai dengan nama prop di komponen Vue Anda.
        return Inertia::render('Angkutan/Index', [ // Sesuaikan path ini jika berbeda
            'angkutans' => $angkutans,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $perusahaans = Perusahaan::all(); // Ambil semua perusahaan
        $mereks = Merek::all(); // Ambil semua merek
        // Pastikan Anda mengembalikan view yang sesuai untuk form pembuatan angkutan
        return Inertia::render('Angkutan/Create', [
            'perusahaans_list' => $perusahaans,
            'mereks_list' => $mereks,
        ]); // Sesuaikan path ini jika berbeda
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'perusahaan_id' => 'required|exists:perusahaans,id',
                'merek_id' => 'required|exists:mereks,id',
                'TNKB' => 'required|string|max:255',
                'No_uji' => 'required|string|max:255',
                'No_KP' => 'required|string|max:255',
                'No_NIB' => 'required|string|max:255',
                'No_SK' => 'required|string|max:255',
                'NO_Mesin' => 'nullable|string|max:255',
                'Tanggal_SK' => 'required|string|max:255',
                'Kode_Trayek' => 'required|string|max:255',
                'No_Seri' => 'required|string|max:255',
                'Daya_Angkut' => 'required|numeric',
                'KG' => 'required|numeric',
                'Tahun_Pembuatan' => 'required|numeric|min:1900|max:' . date('Y'),
                'Alamat' => 'required|string|max:255'
            ]);

            $validatedData['Tahun_Pembuatan'] = $validatedData['Tahun_Pembuatan'] . '-01-01'; // Format tahun pembuatan menjadi YYYY-MM-DD

            Angkutan::create($validatedData);
            return redirect()->route('angkutan.index')->with('success', 'Angkutan created successfully.');
        } catch (\Throwable $th) {
            // Tangani error dengan mengembalikan pesan kesalahan
            return redirect()->back()->with('error', 'Failed to create angkutan: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Temukan angkutan berdasarkan ID, dengan memuat relasi perusahaan dan merek
        $angkutan = Angkutan::with(['perusahaan', 'merek'])->findOrFail($id);

        // Ambil semua data perusahaan dan merek dari database
        $perusahaans = Perusahaan::all();
        $mereks = Merek::all();

        // Render komponen Inertia 'Angkutan/Show' dan teruskan semua data yang diperlukan
        return Inertia::render('Angkutan/Show', [
            'angkutan' => $angkutan,
            'perusahaans_list' => $perusahaans, // Tambahkan ini
            'mereks_list' => $mereks,           // Tambahkan ini
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $angkutan = Angkutan::with(['perusahaan', 'merek'])->findOrFail($id);
        return Inertia::render('Angkutan/Update', [
            'angkutan' => $angkutan,
            'perusahaans_list' => Perusahaan::all(), // Tambahkan ini
            'mereks_list' => Merek::all(),           // Tambahkan ini
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'perusahaan_id' => 'required|exists:perusahaans,id',
                'merek_id' => 'required|exists:mereks,id',
                'TNKB' => 'required|string|max:255',
                'No_uji' => 'required|string|max:255',
                'No_KP' => 'required|string|max:255',
                'No_NIB' => 'required|string|max:255',
                'No_SK' => 'required|string|max:255',
                'Tanggal_SK' => 'required|string|max:255',
                'Kode_Trayek' => 'required|string|max:255',
                'No_Seri' => 'required|string|max:255',
                'Daya_angkut' => 'required|numeric',
                'KG' => 'required|numeric',
                'Alamat' => 'required|string|max:255'
            ]);
            $angkutan = Angkutan::findOrFail($id);
            $request->merge(['Tahun_Pembuatan' => $request->input('Tahun_Pembuatan') . '-01-01']); // Format tahun pembuatan menjadi YYYY-MM-DD
            $angkutan->update($request->all());
            return redirect()->route('angkutan.index')->with('success', 'Angkutan updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to update angkutan: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Angkutan::destroy($id);
            return redirect()->route('angkutan.index')->with('success', 'Angkutan deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to delete angkutan: ' . $th->getMessage());
        }
    }

    public function exportexcel()
    {
        // Logika untuk mengekspor data ke Excel
        // Anda bisa menggunakan library seperti Maatwebsite Excel untuk ini
        // Contoh:
        // return Excel::download(new AngkutanExport, 'angkutan.xlsx');
        return Excel::download(new Angkutan(), 'angkutan.xlsx');
    }
}
