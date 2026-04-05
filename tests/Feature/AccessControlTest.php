<?php

// =========================================================
// TESTS : Contrôle d'accès — Middleware auth & role:admin
// Couvre : protection des routes admin, panier, commandes
// =========================================================

// ── Routes admin protégées ────────────────────────────────

test('un visiteur non connecté ne peut pas accéder au panel admin', function () {
    $this->get('/admin/produit')->assertRedirect('/login');
    $this->get('/admin/utilisateur')->assertRedirect('/login');
    $this->get('/admin/option')->assertRedirect('/login');
    $this->get('/admin/commande')->assertRedirect('/login');
});

test('un utilisateur simple ne peut pas accéder au panel admin', function () {
    $user = createUser();

    $this->actingAs($user)->get('/admin/produit')->assertRedirect('/');
    $this->actingAs($user)->get('/admin/utilisateur')->assertRedirect('/');
    $this->actingAs($user)->get('/admin/commande')->assertRedirect('/');
});

test('un admin peut accéder au panel admin', function () {
    $admin = createAdmin();

    $this->actingAs($admin)->get('/admin/produit')->assertStatus(200);
    $this->actingAs($admin)->get('/admin/utilisateur')->assertStatus(200);
    $this->actingAs($admin)->get('/admin/commande')->assertStatus(200);
});

// ── Routes panier protégées ───────────────────────────────

test('un visiteur non connecté ne peut pas accéder au panier', function () {
    $this->get('/cart')->assertRedirect('/login');
});

test('un visiteur non connecté ne peut pas ajouter au panier', function () {
    $produit = createProduit();

    $this->post("/addCart/{$produit->id}")->assertRedirect('/login');
});

test('un utilisateur connecté peut accéder au panier', function () {
    $user = createUser();

    $this->actingAs($user)->get('/cart')->assertStatus(200);
});

// ── Routes commandes protégées ────────────────────────────

test('un visiteur non connecté ne peut pas finaliser une commande', function () {
    $this->get('/commande/OnStoreCompleted')->assertRedirect('/login');
    $this->get('/commande/OnStoreCancelled')->assertRedirect('/login');
});

test('la liste des commandes n\'est visible que par un admin', function () {
    $user  = createUser();
    $admin = createAdmin();

    // Utilisateur simple → refusé
    $this->actingAs($user)->get('/admin/commande')->assertRedirect('/');

    // Admin → accès autorisé
    $this->actingAs($admin)->get('/admin/commande')->assertStatus(200);
});

test('un utilisateur simple ne peut pas supprimer une commande', function () {
    $user = createUser();

    $this->actingAs($user)
         ->delete('/admin/commande/1')
         ->assertRedirect('/'); // redirigé, pas autorisé
});

// ── Route profil utilisateur ──────────────────────────────

test('un visiteur non connecté ne peut pas accéder au profil', function () {
    $this->get('/userInfo')->assertRedirect('/login');
});

test('un utilisateur connecté peut accéder à son profil', function () {
    $user = createUser();

    $this->actingAs($user)->get('/userInfo')->assertStatus(200);
});

// ── Page publique ─────────────────────────────────────────

test('la page d\'accueil est accessible à tous', function () {
    $this->get('/')->assertStatus(200);
});

test('la fiche d\'un produit est accessible sans connexion', function () {
    $produit = createProduit();

    $this->get("/produit/{$produit->id}")->assertStatus(200);
    $this->get("/produit/{$produit->id}")->assertSee($produit->nom);
});
