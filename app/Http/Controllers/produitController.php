<?php

namespace App\Http\Controllers;

use App\Http\Requests\produitFormRequest;
use App\Models\Image;
use App\Models\Option;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class produitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('listProduits',[
            "produits"=>Produit::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $options = Option::all();
        return view('addProduit',compact('options'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(produitFormRequest $request)
    {

        $validatedData = $request->validated();
        // Définit 'sold' à 0 si la case à cocher n'est pas activée
        $validatedData['sold'] = $request->has('sold') ? 1 : 0;
        $produit = Produit::create($validatedData);
        if ($request->has('options')) {
            $produit->option()->sync($request->input('options'));
        }

          // Vérifier si une image a été téléchargée
          if ($request->hasFile('images')) {
            // Stocker l'image sur le serveur
            $path = $request->file('images')->store('images', 'public');

            // Créer une entrée pour l'image et l'associer au produit
            $image = new Image();
            $image->produit_id = $produit->id;
            $image->image_path = $path;
            $image->save();
        }

        return to_route('admin.produit.index')->with('success','Le produit a été ajouté avec success');
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
        // Récupérer le produit avec son image et ses options
        $produit = Produit::with('image', 'option')->findOrFail($id); // Charge les relations nécessaires
        $options = Option::all();
        if (!$produit) {
            return redirect()->route('admin.produit.index')->with('error', "Produit non trouvé");
        }

        return view('editProduit', ['produit' => $produit,'options'=>$options]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(produitFormRequest $request, string $id)
    {
        $validated = $request->validated();

        $produit = Produit::findOrFail($id);

        $produit->nom = $validated['nom'];
        $produit->description = $validated['description'];
         $produit->quantite = $validated['quantite'];
        $produit->prix_unitaire = $validated['prix_unitaire'];
         //Gérer le statut : si absent, définir comme 0
        $produit->sold = $request->has('sold') ? $validated['sold'] : 0;
        $produit->save();

        if ($request->has('options')) {
            $produit->option()->sync($request->input('options'));
        } else {
            $produit->option()->detach();
        }

        if ($request->hasFile('images')) {
            // Supprimer l'ancienne image, si elle existe
            $oldImage = $produit->image;
            if ($oldImage) {
                Storage::delete('public/' . $oldImage->image_path); // Utiliser l'ancienne image pour supprimer son fichier
                $oldImage->delete(); // Supprimer l'enregistrement de l'image
            }


            // Enregistrer la nouvelle image
            $path = $request->file('images')->store('images', 'public');
            $newImage = new Image();
            $newImage->produit_id = $produit->id;
            $newImage->image_path = $path;
            $newImage->save();
        }

        return redirect()->route('admin.produit.index')->with('success', 'Produit mis à jour avec succès.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produit = Produit::findOrFail($id);
        // Supprimer l'image associée avant de supprimer le produit
        $image = $produit->image()->first();  // Assurez-vous que la relation est définie dans le modèle Produit
        if ($image) {
            Storage::delete('public/' . $image->image_path);
            $image->delete();
        }
        $produit->delete();
        return redirect()->route('admin.produit.index')->with('success', 'produit supprimé avec succès.');
    }
}
