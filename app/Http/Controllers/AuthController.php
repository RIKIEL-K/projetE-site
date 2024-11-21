<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\loginRequest;
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

        return view ('connexion');
    }
}

