<?php

namespace App\Http\Controllers;

use App\Models\Merek;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MerekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mereks = Merek::paginate(10);
        return Inertia::render("Merek/Index", [
            'mereks' => Merek::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Merek/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_merek' => 'required|string|max:255',
            ]);
            Merek::create($request->all());
            return redirect()->route('merek.index')->with('success', 'Merek created successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to create merek: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render("Merek/Edit", [
            'merek' => Merek::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_merek' => 'required|string|max:255',
        ]);
        try {
            $merek = Merek::findOrFail($id);
            $merek->update($request->all());
            return redirect()->route('merek.index')->with('success', 'Merek updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to update merek: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $merek = Merek::findOrFail($id);
            $merek->delete();
            return redirect()->route('merek.index')->with('success', 'Merek deleted successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Failed to delete merek: ' . $th->getMessage());
        }
    }
}
