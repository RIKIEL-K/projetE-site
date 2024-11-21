<?php

namespace App\Http\Controllers;

use App\Http\Requests\OptionFormRequest;
use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('listOptions',[
            "options"=>Option::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('addOption');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OptionFormRequest $request)
    {
        $validatedData = $request->validated();
        $produit = Option::create($validatedData);
        return to_route('admin.option.index')->with('success',"L'option a été ajouté avec success");
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
        $option=Option::findOrFail($id);
        if (!$option) {
            return redirect()->route('admin.option.index')->with('error', "option non trouvé");
        }

        return view('editOption', ['option' => $option]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OptionFormRequest $request, string $id)
    {
        $validated = $request->validated();

        $produit = Option::findOrFail($id);

        $produit->nom = $validated['nom'];
        $produit->save();

        return redirect()->route('admin.option.index')->with('success', 'Option mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produit = Option::findOrFail($id);
        $produit->delete();
        return redirect()->route('admin.option.index')->with('success', 'option supprimé avec succès.');
    }
}
