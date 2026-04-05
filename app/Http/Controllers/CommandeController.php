<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CommandeController extends Controller
{
    /**
     * Liste des commandes (admin uniquement — protege par role:admin dans routes)
     */
    public function index(){
        $commandes = Commande::with('user', 'items')->get();
        return view('listCommandes', ['commandes' => $commandes]);
    }

    /**
     * Commande validee par PayPal
     * le total est recalcule cote serveur depuis la session,
     *               jamais depuis les parametres GET/POST du client.
     */
    public function OnStoreCompleted(Request $request){

        if (!Session::has('cart') || empty(Session::get('cart'))) {
            return redirect()->route('index');
        }

        $cart = Session::get('cart');

        // Recalcul du total cote serveur
        $total = array_sum(array_map(function ($item) {
            return $item['prix'] * $item['qte'];
        }, $cart));

        $order = Commande::create([
            'user_id' => Auth::id(),
            'total'   => $total,
            'status'  => 'termine',
        ]);

        foreach ($cart as $id => $item) {
            $order->items()->create([
                'produit_id' => $id,
                'quantite'   => $item['qte'],
                'prix'       => $item['prix'],
            ]);

            // Decrementer le stock
            $produit = Produit::find($id);
            if ($produit) {
                $produit->quantite = max(0, $produit->quantite - $item['qte']);
                $produit->save();
            }
        }

        Session::forget('cart');

        return redirect()->route('index')->with('success', 'Commande passee avec succes !');
    }

    /**
     * Paiement annule — on enregistre la commande comme echouee mais on garde le panier
     */
    public function OnStoreCancelled(Request $request){

        if (!Session::has('cart') || empty(Session::get('cart'))) {
            return redirect()->route('index');
        }

        $cart = Session::get('cart');

        // Recalcul du total cote serveur
        $total = array_sum(array_map(function ($item) {
            return $item['prix'] * $item['qte'];
        }, $cart));

        $order = Commande::create([
            'user_id' => Auth::id(),
            'total'   => $total,
            'status'  => 'echoue',
        ]);

        foreach ($cart as $id => $item) {
            $order->items()->create([
                'produit_id' => $id,
                'quantite'   => $item['qte'],
                'prix'       => $item['prix'],
            ]);
        }

        // Le panier est conserve pour que l'utilisateur puisse reessayer
        return redirect()->route('cart.view')->with('danger', 'Paiement annule. Votre panier est conserve.');
    }

    /**
     * Supprimer une commande (admin uniquement — protege par role:admin dans routes)
     */
    public function supprimer(string $id){
        $commande = Commande::findOrFail($id);
        $commande->items()->delete();
        $commande->delete();
        return redirect()->route('admin.commande.index')->with('success', 'Commande supprimee avec succes.');
    }
}
