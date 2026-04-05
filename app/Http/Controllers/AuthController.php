<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\loginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\utilisateurFormRequest;


class AuthController extends Controller
{
    public function doLogin(loginRequest $request){

        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Stocker les informations utilisateur dans la session
            // ne jamais stocker le hash du password en session
            $user = Auth::user();
            $request->session()->put('user', [
                'id'             => $user->id,
                'nom'            => $user->nom,
                'telephone'      => $user->telephone,
                'date_naissance' => $user->date_naissance,
                'prenom'         => $user->prenom,
                'email'          => $user->email,
                'statut'         => $user->statut,
            ]);

            // Conserver le panier s'il existait avant la connexion
            $cart = Session::get('cart', []);
            $request->session()->put('cart', $cart);

            return redirect()->intended();
        } else {
            return back()->withErrors([
                'email' => 'Identifiant ou mot de passe incorrect',
            ])->onlyInput('email');
        }
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('login')->with('success', 'Deconnexion reussie');
    }

    public function login(){
        return view('connexion');
    }

    public function sign(){
        return view('inscriptionUser');
    }

    public function doSignIn(utilisateurFormRequest $request){
        $validatedData = $request->validated();

        // Hash le mot de passe avant de le sauvegarder
        $validatedData['password'] = Hash::make($validatedData['password']);

        //forcer statut=0 pour empecher l'elevation de privileges via formulaire
        $validatedData['statut'] = 0;

        User::create($validatedData);

        return to_route('login')->with('success', "Utilisateur enregistre avec succes");
    }
}
