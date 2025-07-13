<?php

namespace App\Http\Controllers;

use App\Exports\AngkutanExport;
use App\Imports\ImportAngkutan;
use App\Models\Angkutan;
use App\Models\JenisAngkutan;
use App\Models\Merek;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class AngkutanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filterPerusahaan = $request->input('perusahaan'); // Get the 'perusahaan' query parameter
        $filterJenisAngkutan = $request->input('jenis_angkutan'); // Get the 'jenis_angkutan' query parameter
        $query = Angkutan::query();
        $query = Angkutan::with(['perusahaan', 'merek', 'jenisAngkutan']);
        // dd(JenisAngkutan
        //     ::all());

        if ($filterPerusahaan) {
            $query->whereHas('perusahaan', function ($q) use ($filterPerusahaan) {
                $q->where('nama_perusahaan', 'like', '%' . $filterPerusahaan . '%');
            });
        }

        if ($filterJenisAngkutan) {
            $query->whereHas('jenisAngkutan', function ($q) use ($filterJenisAngkutan) {
                $q->where('Nama_Jenis_Angkutan', 'like', '%' . $filterJenisAngkutan . '%');
            });
        }

        $angkutans = $query->paginate(10); // Apply pagination after filtering

        // dd($angkutans->toArray());
        return Inertia::render('Angkutan/Index', [
            'angkutans' => $angkutans,
            'filterPerusahaan' => $filterPerusahaan,
            'filterJenisAngkutan' => $filterJenisAngkutan,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $perusahaans = Perusahaan::all(); // Ambil semua perusahaan
        $mereks = Merek::all(); // Ambil semua merek
        $jenis_angkutans = JenisAngkutan::all(); // Ambil semua jenis angkutan
        // Pastikan Anda mengembalikan view yang sesuai untuk form pembuatan angkutan
        return Inertia::render('Angkutan/Create', [
            'perusahaans_list' => $perusahaans,
            'mereks_list' => $mereks,
            'jenis_angkutans_list' => $jenis_angkutans,
        ]); // Sesuaikan path ini jika berbeda
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Log::info('test');
        try {
            $validator = Validator::make($request->all(), [
                'perusahaan_id' => 'required|exists:perusahaans,id',
                'merek_id' => 'required|exists:mereks,id',
                'jenis_angkutan_id' => 'required|exists:jenis_angkutans,id',
                'Masa_Berlaku_KP_Start_Date' => 'nullable|date|before_or_equal:Masa_Berlaku_KP_End_Date',
                'Masa_Berlaku_KP_End_Date' => 'nullable|date|after_or_equal:Masa_Berlaku_KP_Start_Date',
                'Masa_Berlaku_SK_Start_Date' => 'nullable|date|before_or_equal:Masa_Berlaku_SK_End_Date',
                'Masa_Berlaku_SK_End_Date' => 'nullable|date|after_or_equal:Masa_Berlaku_SK_Start_Date',
                'keterangan_perizinan' => 'nullable|boolean',
                'NIK' => 'nullable|string|max:255',
                'Jenis_BBM' => 'nullable|string|max:255',
                'Masa_Berlaku_STNK' => 'nullable|date',
                'No_Rangka' => 'nullable|string|max:255',
                'No_Trayek' => 'nullable|string|max:255',
                'TNKB' => 'nullable|string|max:255',
                'No_uji' => 'nullable|string|max:255',
                'No_KP' => 'nullable|string|max:255',
                'No_NIB' => 'nullable|string|max:255',
                'No_SK' => 'nullable|string|max:255',
                'No_Mesin' => 'nullable|string|max:255',
                // 'Tanggal_SK' => 'nullable|string|max:255',
                'Kode_Trayek' => 'nullable|string|max:255',
                'No_Seri' => 'nullable|string|max:255',
                'Daya_Angkut_Orang' => 'nullable|numeric',
                'Daya_Angkut_KG' => 'nullable|numeric',
                'Tahun_Pembuatan' => 'nullable|numeric',
                'Alamat' => 'nullable|string|max:255',
            ]);
            // Jika validasi berhasil, lanjutkan dengan logika penyimpanan data
            Angkutan::create($validator->validated());

            return redirect()->route('angkutan.index')->with('success', 'Angkutan created successfully.');
        } catch (\Throwable $th) {
            Log::info($th);
            return redirect()->back()->with('error', 'Failed to create angkutan: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Log::info('Show Angkutan ID: ' . $id);
        // dd($id);
        // Temukan angkutan berdasarkan ID, dengan memuat relasi perusahaan dan merek
        $angkutan = Angkutan::with(['perusahaan', 'merek', 'jenisAngkutan'])->findOrFail($id);

        // Ambil semua data perusahaan dan merek dari database
        $perusahaans = Perusahaan::all();
        $mereks = Merek::all();
        $jenis_angkutans = JenisAngkutan::all();

        // dd($angkutan->toArray());
        // Render komponen Inertia 'Angkutan/Show' dan teruskan semua data yang diperlukan
        return Inertia::render('Angkutan/Show', [
            'angkutan' => $angkutan,
            'perusahaans_list' => $perusahaans, // Tambahkan ini
            'mereks_list' => $mereks,           // Tambahkan ini
            'jenis_angkutans_list' => $jenis_angkutans, // Tambahkan ini
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
            'mereks_list' => Merek::all(),
            'jenis_angkutans_list' => JenisAngkutan::all(), // Tambahkan ini
        ]);
        // dd($angkutan->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        // Log::info('Update Angkutan Request: ', $request->all());
        try {
            $request->validate([
                'perusahaan_id' => 'required|exists:perusahaans,id',
                'merek_id' => 'required|exists:mereks,id',
                'jenis_angkutan_id' => 'required|exists:jenis_angkutans,id',
                'Masa_Berlaku_Kp_Start_Date' => 'nullable|date',
                'Masa_Berlaku_Kp_End_Date' => 'nullable|date',
                'Masa_Berlaku_Kk_Start_Date' => 'nullable|date',
                'Masa_Berlaku_Kk_End_Date' => 'nullable|date',
                'keterangan_perizinan' => 'nullable|boolean',
                'NIK' => 'nullable|string|max:255',
                'Jenis_BBM' => 'nullable|string|max:255',
                'Masa_Berlaku_STNK' => 'nullable|date',
                'No_Rangka' => 'nullable|string|max:255',
                'No_Trayek' => 'nullable|string|max:255',
                'TNKB' => 'nullable|string|max:255',
                'No_uji' => 'nullable|string|max:255',
                'No_KP' => 'nullable|string|max:255',
                'No_NIB' => 'nullable|string|max:255',
                'No_SK' => 'nullable|string|max:255',
                'No_Mesin' => 'nullable|string|max:255',
                'No_KP' => 'nullable|string|max:255',
                // 'Tanggal_SK' => 'nullable|string|max:255',
                'Kode_Trayek' => 'nullable|string|max:255',
                'No_Seri' => 'nullable|string|max:255',
                'Daya_Angkut' => 'nullable|numeric',
                'KG' => 'nullable|numeric',
                'Tahun_Pembuatan' => 'nullable|numeric|min:1900|max:' . date('Y'),
                'Alamat' => 'nullable|string|max:255'
            ]);
            Log::info($request->all());
            $angkutan = Angkutan::findOrFail($id);
            // $request->merge(['Tahun_Pembuatan' => $request->input('Tahun_Pembuatan') . '-01-01']); // Format tahun pembuatan menjadi YYYY-MM-DD
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

    public function exportExcel()
    {
        // Menggunakan Maatwebsite Excel untuk mengekspor data Angkutan ke file Excel
        return Excel::download(new AngkutanExport, 'angkutan.xlsx');
    }

    public function importExcel(Request $request)
    {
        // Validasi file yang diunggah
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);
        // dd($request->all());
        // Log::info('Import Angkutan Request: ', $request->all());

        // Menggunakan Maatwebsite Excel untuk mengimpor data Angkutan dari file Excel
        Excel::import(new ImportAngkutan, $request->file('file'));
        // dd($request->file('file')->getClientOriginalName());
        // Log::info('test import');
        return redirect()->route('angkutan.index')->with('success', 'Angkutan imported successfully.');
    }
}
