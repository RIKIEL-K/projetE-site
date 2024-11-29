<?php

namespace App\Http\Controllers;

use App\Http\Requests\utilisateurFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class utilisateurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view ('listUtilisateurs',[
            "utilisateurs"=>User::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('addUtilisateur');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(utilisateurFormRequest $request)
    {
         // Valider les données
        $validatedData = $request->validated();

        // Définit 'statut' à 0 si la case à cocher n'est pas activée
        $validatedData['statut'] = $request->has('statut') ? 1 : 0;

        // Hash le mot de passe avant de le sauvegarder
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Crée l'utilisateur
        $user = User::create($validatedData);

        return to_route('admin.utilisateur.index')->with('success', "Utilisateur a été enrégistré avec succès");
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
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('admin.utilisateur.index')->with('error', "Utilisateur non trouvé");
        }

        return view('editUtilisateur', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(utilisateurFormRequest $request, string $id)
    {
        $validated = $request->validated();

        $user = User::findOrFail($id);

        $user->nom = $validated['nom'];
        $user->prenom = $validated['prenom'];
         $user->telephone = $validated['telephone'];
        $user->email = $validated['email'];
        $user->date_naissance = $validated['date_naissance'];
         // Gérer le statut : si absent, définir comme 0
        $user->statut = $request->has('statut') ? $validated['statut'] : 0;
        $user->password = $validated['password'];

        $user->save();

        return redirect()->route('admin.utilisateur.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.utilisateur.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
    // Fonction pour initialiser le panier de l'utilisateur
    private function initializeUserCart()
    {
        // Vérifier si l'utilisateur a un panier déjà existant en session
        if (!session()->has('cart') || empty(session()->get('cart'))) {
            // Créer un panier vide pour l'utilisateur si aucun panier n'existe
            session()->put('cart', []);
        }
    }
}
