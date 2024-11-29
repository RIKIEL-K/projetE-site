<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CommandeController extends Controller
{

    public function index(){

    $commandes = Commande::with('user', 'items')->get(); // Récupère toutes les commandes avec les détails
    return view('listCommandes', [
        'commandes'=>$commandes
    ]);

    }
    public function OnStoreCompleted(Request $request){

        // Vérifiez si le panier est vide dans la session
        if (!Session::has('cart') || empty(Session::get('cart'))) {
            return redirect()->route('index');
        }

        // Crée une nouvelle commande pour l'utilisateur
        $order = Commande::create([
            'user_id' => Auth::id(),            // Associe la commande à l'utilisateur connecté
            'total' => $request->input('total'), // Total de la commande (fournir via le formulaire ou calculé)
            'status' => 'terminé',               // Statut initial de la commande
        ]);

        $cart = Session::get('cart');

        // Créer les items de la commande
        foreach ($cart as $id => $item) {
            $order->items()->create([
                'produit_id' => $id,        // ID du produit
                'quantite' => $item['qte'], // Quantité commandée
                'prix' => $item['prix'],    // Prix unitaire
            ]);
        }
         // Vider le panier après la commande
         Session::forget('cart');

         return redirect()->route('index')->with('success', 'Commande passée avec succès!');

    }
    public function OnStoreCancelled(Request $request){

        $request->validate([
            'total' => 'required|numeric|min:0',
        ]);

        // Vérifiez si le panier est vide dans la session
        if (!Session::has('cart') || empty(Session::get('cart'))) {
            return redirect()->route('index');
        }

        // Crée une nouvelle commande pour l'utilisateur
        $order = Commande::create([
            'user_id' => Auth::id(),            // Associe la commande à l'utilisateur connecté
            'total' => $request->input('total'), // Total de la commande (fournir via le formulaire ou calculé)
            'status' => 'echoué',               // Statut initial de la commande
        ]);

        $cart = Session::get('cart');

        // Créer les items de la commande
        foreach ($cart as $id => $item) {
            $order->items()->create([
                'produit_id' => $id,        // ID du produit
                'quantite' => $item['qte'], // Quantité commandée
                'prix' => $item['prix'],    // Prix unitaire
            ]);
        }

         return redirect()->route('cart.view')->with('danger', 'Commande echoué!');

    }

    public function supprimer(string $id){

        $commande = Commande::findOrFail($id);

        // Supprimer les items associés à la commande
         $commande->items()->delete();

        $commande->delete();
        return redirect()->route('commande.index')->with('success', 'Commande supprimée avec succès.');
    }

}
