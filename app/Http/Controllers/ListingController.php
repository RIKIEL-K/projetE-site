<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produit = Produit::with('option','image')->findOrFail($id);

        $imagePath = $produit->image ? asset('storage/' . $produit->image->image_path) : asset('images/no-image.png');
          // Récupérer le panier depuis la session
          $cart = Session::get('cart', []);

          $cartCount = array_reduce($cart, function ($count, $item) {
              return $count + $item['qte'];
          }, 0);

        if (!$produit) {
            return redirect()->route('/')->with('error', "Produit non trouvé");
        }
        return view ('monProduit',[
            'produit'=>$produit,
            'cartCount'=>$cartCount,
            'imagePath'=>$imagePath
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
