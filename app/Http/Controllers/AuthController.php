<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\loginRequest;
use App\Http\Requests\utilisateurFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function doLogin(loginRequest $request){

        $credentials = $request->validated();
          if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended();
          } else {
            return back()->withErrors([
                     'email'=>'identifiant ou mot de passe incorrect',
                  ])->onlyInput('email');
          }
    }
    public function logout(){
        Auth::logout();
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

