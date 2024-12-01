<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\utilisateurFormRequest;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
    $userId = $user['id'];

      // Définir les règles de validation
      $validated = Validator::make($request->all(), [
        'nom' => ['required', 'min:2'],
        'prenom' => ['required', 'min:2'],
        'telephone' => ['required'],
        'email' => [
            'required',
            'email',
            Rule::unique('users')->ignore($userId), // Ignorer l'email de l'utilisateur actuel
        ],
        'date_naissance' => ['required'],
        'password' => ['nullable', 'min:6'],
    ]);// Exécuter la validation

    $user = User::findOrFail($user['id']); // Récupère l'utilisateur en base

    if (empty($user)) {
        return redirect()->route('login')->withErrors('Session utilisateur expirée. Veuillez vous reconnecter.');
    }

    $user->nom = $request->input('nom');
    $user->prenom = $request->input('prenom');
    $user->telephone = $request->input('telephone');
    $user->email = $request->input('email');
    $user->date_naissance = $request->input('date_naissance');

    // Si un mot de passe est fourni, on le met à jour, sinon on le garde inchangé
    if ($request->filled('password')) {
        $user->password = Hash::make($request->input('password'));
    }

    $user->save();

    // Met à jour les données dans la session
    $request->session()->put('user', [
        'id' => $user->id,
        'nom' => $user->nom,
        'telephone' => $user->telephone,
        'date_naissance' => $user->date_naissance,
        'prenom' => $user->prenom,
        'email' => $user->email,
        'password' => $user->password,
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
