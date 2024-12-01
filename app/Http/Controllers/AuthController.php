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
            $user = Auth::user(); //recupere les infos sur l'utilisateur connecté et les stocke dans $user
            $request->session()->put('user', [
                'id' => $user->id,
                'nom' => $user->nom,
                'telephone' => $user->telephone,
                'date_naissance' => $user->date_naissance,
                'prenom' => $user->prenom,
                'email' => $user->email,
                'password'=>$user->password,
            ]);
            // Si l'utilisateur a déjà un panier, on le récupere
            $cart = Session::get('cart', []);
            //on l'ajoute à la session
            $request->session()->put('cart', $cart);
            return redirect()->intended();
          } else {
            return back()->withErrors([
                     'email'=>'identifiant ou mot de passe incorrect',
                  ])->onlyInput('email');
          }
    }
    public function logout(Request $request){
        Auth::logout();
        // Supprimer les données de session utilisateur et panier
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('login')->with('success','déconnexion reussi');
    }
    public function login(){
        // Exemple de création d'un utilisateur
            // User::create([
            //     'nom' => 'admin',
            //     'prenom' => 'admin',
            //     'email' => 'admin@admin.com',
            //     'password' => Hash::make('admin12345'),
            //     'telephone' => '123456789',
            //     'date_naissance' => '1990-01-01',
            // ]);

        return view ('connexion');
    }
    public function sign(){
        return view('inscriptionUser');
    }
        public function doSignIn(utilisateurFormRequest $request){
            // Valider les données
            $validatedData = $request->validated();
          // Hash le mot de passe avant de le sauvegarder
            $validatedData['password'] = Hash::make($validatedData['password']);

            // Crée l'utilisateur
            $user = User::create($validatedData);

            return to_route('login')->with('success', "Utilisateur a été enrégistré avec succès");
    }

}

