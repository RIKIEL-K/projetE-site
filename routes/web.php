<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\produitController;
use App\Http\Controllers\utilisateurController;
use Illuminate\Support\Facades\Route;

Route::prefix("/admin")->name("admin.")->middleware('auth')->group(function(){
    Route::resource("produit",produitController::class);
    Route::resource("option",OptionController::class);
    Route::resource("utilisateur",utilisateurController::class);
 });
Route::get('/',[HomeController::class,'index']);
Route::get('/produit/{id}',[ListingController::class,'show'])->name('produit.show');
Route::get('/login',[AuthController::class,'login'])->middleware('guest')->name('login');
Route::post('/login',[AuthController::class,'doLogin']);
Route::delete('/logout',[AuthController::class,'logout'])->middleware('auth')->name('logout');
