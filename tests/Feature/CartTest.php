<?php

// =========================================================
// TESTS : Panier (CartController)
// Couvre : ajout, mise à jour, suppression, stock, total
// =========================================================

// ── Ajout au panier ───────────────────────────────────────

test('un utilisateur connecté peut ajouter un produit au panier', function () {
    $user    = createUser();
    $produit = createProduit(['quantite' => 5]);

    $response = $this->actingAs($user)->post("/addCart/{$produit->id}");

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $cart = session('cart');
    expect($cart)->toHaveKey($produit->id);
    expect($cart[$produit->id]['qte'])->toBe(1);
    expect($cart[$produit->id]['name'])->toBe($produit->nom);
});

test('ajouter le même produit deux fois incrémente la quantité', function () {
    $user    = createUser();
    $produit = createProduit(['quantite' => 10]);

    $this->actingAs($user)->post("/addCart/{$produit->id}");
    $this->actingAs($user)->post("/addCart/{$produit->id}");

    $cart = session('cart');
    expect($cart[$produit->id]['qte'])->toBe(2);
});

test('on ne peut pas ajouter un produit en rupture de stock', function () {
    $user    = createUser();
    $produit = createProduit(['quantite' => 0]);

    $response = $this->actingAs($user)->post("/addCart/{$produit->id}");

    $response->assertSessionHasErrors('stock');
    expect(session('cart'))->toBeEmpty();
});

test('on ne peut pas dépasser la quantité en stock', function () {
    $user    = createUser();
    $produit = createProduit(['quantite' => 2]);

    // Ajouter 2 fois (max du stock)
    $this->actingAs($user)->post("/addCart/{$produit->id}");
    $this->actingAs($user)->post("/addCart/{$produit->id}");

    // Tenter une 3e fois — doit être refusé
    $response = $this->actingAs($user)->post("/addCart/{$produit->id}");
    $response->assertSessionHasErrors('stock');

    $cart = session('cart');
    expect($cart[$produit->id]['qte'])->toBe(2); // inchangé
});

test('un produit inexistant retourne une erreur 404', function () {
    $user = createUser();

    $this->actingAs($user)->post('/addCart/99999')->assertStatus(404);
});

// ── Vue du panier ─────────────────────────────────────────

test('la page panier affiche les produits ajoutés', function () {
    $user    = createUser();
    $produit = createProduit(['nom' => 'Casque Audio', 'quantite' => 5]);

    $this->actingAs($user)->post("/addCart/{$produit->id}");

    $response = $this->actingAs($user)->get('/cart');
    $response->assertStatus(200);
    $response->assertSee('Casque Audio');
});

test('la page panier affiche un message si le panier est vide', function () {
    $user = createUser();

    $response = $this->actingAs($user)->get('/cart');
    $response->assertStatus(200);
    $response->assertSee('Votre panier est vide');
});

test('le total du panier est correctement calculé', function () {
    $user     = createUser();
    $produit1 = createProduit(['prix_unitaire' => '10.00', 'quantite' => 5]);
    $produit2 = createProduit(['email' => null, 'nom' => 'Produit B', 'prix_unitaire' => '20.00', 'quantite' => 5]);

    $this->actingAs($user)->post("/addCart/{$produit1->id}"); // +10.00
    $this->actingAs($user)->post("/addCart/{$produit2->id}"); // +20.00
    $this->actingAs($user)->post("/addCart/{$produit1->id}"); // +10.00

    $response = $this->actingAs($user)->get('/cart');
    $response->assertSee('40'); // total = 40.00
});

// ── Mise à jour de quantité ───────────────────────────────

test('l\'utilisateur peut mettre à jour la quantité d\'un article', function () {
    $user    = createUser();
    $produit = createProduit(['quantite' => 10]);

    $this->actingAs($user)->post("/addCart/{$produit->id}");

    $response = $this->actingAs($user)->post('/cart/update', [
        'product_id' => $produit->id,
        'qte'        => 5,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    expect(session('cart')[$produit->id]['qte'])->toBe(5);
});

test('la quantité minimale pour une mise à jour est 1', function () {
    $user    = createUser();
    $produit = createProduit(['quantite' => 10]);

    $this->actingAs($user)->post("/addCart/{$produit->id}");

    $response = $this->actingAs($user)->post('/cart/update', [
        'product_id' => $produit->id,
        'qte'        => 0, // invalide
    ]);

    $response->assertSessionHasErrors('qte');
});

// ── Suppression du panier ─────────────────────────────────

test('l\'utilisateur peut supprimer un article de son panier', function () {
    $user    = createUser();
    $produit = createProduit(['quantite' => 5]);

    $this->actingAs($user)->post("/addCart/{$produit->id}");
    expect(session('cart'))->toHaveKey($produit->id);

    $response = $this->actingAs($user)->delete("/cart/remove/{$produit->id}");
    $response->assertSessionHas('success');

    expect(session('cart'))->not->toHaveKey($produit->id);
});
