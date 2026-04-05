<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {

        $produits = Produit::all();

        // Récupérer le panier depuis la session
        $cart = Session::get('cart', []);

        // Calculer le nombre total d'articles dans le panier
        $cartCount = array_reduce($cart, function ($count, $item) {
            return $count + $item['qte'];
        }, 0);

        return view('home', data: [
            'produits' => $produits,
            'cartCount' => $cartCount
        ]);
    }

    public function getUserInfo(){
        $user = Session::get('user', []);
            // Récupérer le panier depuis la session
        $cart = Session::get('cart', []);

        // Calculer le nombre total d'articles dans le panier
        $cartCount = array_reduce($cart, function ($count, $item) {
            return $count + $item['qte'];
        }, 0);

        return view ('userInfo',[
            'user'=>$user,
            'cartCount' => $cartCount
        ]);
    }



    public function update(Request $request)
{
    $user = Session::get('user', []);
    $userId = $user['id'] ?? null;

    if (!$userId) {
        return redirect()->route('login')->withErrors('Session utilisateur expirée. Veuillez vous reconnecter.');
    }

    // Définir les règles de validation et les appliquer réellement
    $validated = $request->validate([
        'nom'           => ['required', 'min:2'],
        'prenom'        => ['required', 'min:2'],
        'telephone'     => ['required'],
        'email'         => [
            'required',
            'email',
            Rule::unique('users')->ignore($userId),
        ],
        'date_naissance' => ['required'],
        'password'      => ['nullable', 'min:6'],
    ]);

    $user = User::findOrFail($userId);

    $user->nom           = $validated['nom'];
    $user->prenom        = $validated['prenom'];
    $user->telephone     = $validated['telephone'];
    $user->email         = $validated['email'];
    $user->date_naissance = $validated['date_naissance'];

    // Si un mot de passe est fourni, on le met à jour
    if (!empty($validated['password'])) {
        $user->password = Hash::make($validated['password']);
    }

    $user->save();

    // Met à jour les données dans la session (sans exposer le password)
    $request->session()->put('user', [
        'id'             => $user->id,
        'nom'            => $user->nom,
        'telephone'      => $user->telephone,
        'date_naissance' => $user->date_naissance,
        'prenom'         => $user->prenom,
        'email'          => $user->email,
        'statut'         => $user->statut,
    ]);

    return redirect()->route('index')->with('success', 'Modification effectuée avec succès');
}




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
