<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{

     public function viewCart()
     {
        $cart = Session::get('cart', []);
        $total = array_sum(array_map(function ($item) {
            return $item['prix'] * $item['qte']; // Calcul du total
        }, $cart));

           // Calculer le nombre total d'articles dans le panier
        $cartCount = array_reduce($cart, function ($count, $item) {
            return $count + $item['qte'];
        }, 0);


        return view('cart', compact('cart', 'total','cartCount'));
     }


    public function addToCart($id)
    {
        $produit = Produit::findOrFail($id); // Vérifier si le produit existe

        $cart = Session::get('cart', []); // Récupérer le panier actuel

        // Définir une image par défaut si l'image du produit n'existe pas
        $imagePath = $produit->image ? asset('storage/' . $produit->image->image_path) : asset('images/no-image.png');

        if (isset($cart[$id])) {
            $cart[$id]['qte'] += 1; // Augmenter la quantité
        } else {
            $cart[$id] = [
                'name' => $produit->nom,
                'prix' => $produit->prix_unitaire,
                'qte' => 1,
                'image' => $imagePath,
            ];
        }

        Session::put('cart', $cart); // Sauvegarder le panier
        return back()->with('success', 'Produit ajouté au panier.');
    }


    public function updateCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'qte' => 'required|integer|min:1',
        ]);

        $cart = Session::get('cart', []);


        if (isset($cart[$validated['product_id']])) {
            $cart[$validated['product_id']]['qte'] = $validated['qte'];
            Session::put('cart', $cart);
            return back()->with('success', 'Quantité mise à jour.');
        }

        return back()->withErrors(['error' => 'Produit introuvable dans le panier.']);
    }
   
     public function removeFromCart($id)
     {
         $cart = Session::get('cart', []);

         if (isset($cart[$id])) {
             unset($cart[$id]);
             Session::put('cart', $cart);
             return back()->with('success', 'Produit supprimé du panier.');
         }

         return back()->withErrors(['error' => 'Produit introuvable dans le panier.']);
     }


}
