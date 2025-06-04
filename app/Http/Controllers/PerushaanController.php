<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PerushaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perusahans = Perusahaan::paginate(10);
        return Inertia::render("Perusahaan/Index", [
            'perusahaans' => Perusahaan::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Perusahaan/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_perusahaan' => 'required|string|max:255',

            ]);
            Perusahaan::create($request->all());
            return redirect()->route('perusahaan.index')->with('success', 'Perusahaan created successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to create perusahaan: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render("Perusahaan/Show", [
            'perusahaan' => Perusahaan::findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render("Perusahaan/Edit", [
            'perusahaan' => Perusahaan::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
        ]);
        try {
            $perusahaan = Perusahaan::findOrFail($id);
            $perusahaan->update($request->all());
            return redirect()->route('perusahaan.index')->with('success', 'Perusahaan updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to update perusahaan: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $perusahaan = Perusahaan::findOrFail($id);
            $perusahaan->delete();
            return redirect()->route('perusahaan.index')->with('success', 'Perusahaan deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to delete perusahaan: ' . $th->getMessage());
        }
    }
}
