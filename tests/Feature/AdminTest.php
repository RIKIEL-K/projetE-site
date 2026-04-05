<?php

// =========================================================
// TESTS : Gestion Admin — Produits, Utilisateurs, Commandes
// Couvre : CRUD produit, gestion utilisateurs, sécurité admin
// =========================================================

use App\Models\Commande;
use App\Models\User;

// ── Gestion des produits (admin) ──────────────────────────

test('un admin peut voir la liste des produits', function () {
    $admin = createAdmin();
    createProduit(['nom' => 'Chaise Ergonomique']);

    $response = $this->actingAs($admin)->get('/admin/produit');
    $response->assertStatus(200);
    $response->assertSee('Chaise Ergonomique');
});

test('un admin peut accéder au formulaire d\'ajout de produit', function () {
    $admin = createAdmin();

    $this->actingAs($admin)->get('/admin/produit/create')->assertStatus(200);
});

test('un admin peut voir la fiche détail d\'un produit', function () {
    $admin   = createAdmin();
    $produit = createProduit(['nom' => 'Bureau Standing']);

    $this->actingAs($admin)
         ->get("/produit/{$produit->id}")
         ->assertStatus(200)
         ->assertSee('Bureau Standing');
});

// ── Gestion des utilisateurs (admin) ─────────────────────

test('un admin peut voir la liste des utilisateurs', function () {
    $admin = createAdmin();
    createUser(['email' => 'client@example.com', 'nom' => 'Durand']);

    $response = $this->actingAs($admin)->get('/admin/utilisateur');
    $response->assertStatus(200);
    $response->assertSee('Durand');
});

test('un admin peut créer un nouvel utilisateur', function () {
    $admin = createAdmin();

    $response = $this->actingAs($admin)->post('/admin/utilisateur', [
        'nom'            => 'Nouveau',
        'prenom'         => 'Client',
        'email'          => 'nouveau@example.com',
        'telephone'      => '0699887766',
        'date_naissance' => '1988-03-20',
        'password'       => 'password123',
        'statut'         => 0,
    ]);

    $response->assertRedirect('/admin/utilisateur');
    $this->assertDatabaseHas('users', ['email' => 'nouveau@example.com']);
});

test('un admin peut promouvoir un utilisateur en admin via la modification', function () {
    $admin  = createAdmin();
    $client = createUser(['email' => 'futuradmin@example.com']);

    $this->actingAs($admin)->put("/admin/utilisateur/{$client->id}", [
        'nom'            => $client->nom,
        'prenom'         => $client->prenom,
        'email'          => $client->email,
        'telephone'      => $client->telephone,
        'date_naissance' => $client->date_naissance,
        'statut'         => 1, // promotion admin
    ]);

    expect(User::find($client->id)->statut)->toBe(1);
});

test('un admin ne peut pas se supprimer lui-même', function () {
    $admin = createAdmin();

    $response = $this->actingAs($admin)->delete("/admin/utilisateur/{$admin->id}");

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('users', ['id' => $admin->id]); // toujours en BDD
});

test('un admin peut supprimer un autre utilisateur', function () {
    $admin  = createAdmin();
    $client = createUser(['email' => 'asupprimer@example.com']);

    $this->actingAs($admin)->delete("/admin/utilisateur/{$client->id}");

    $this->assertDatabaseMissing('users', ['email' => 'asupprimer@example.com']);
});

test('la modification d\'un utilisateur ne nécessite pas de nouveau mot de passe', function () {
    $admin  = createAdmin();
    $client = createUser(['email' => 'nomdchange@example.com']);

    // Envoi sans champ password — ne doit pas planter
    $response = $this->actingAs($admin)->put("/admin/utilisateur/{$client->id}", [
        'nom'            => 'NomModifie',
        'prenom'         => $client->prenom,
        'email'          => $client->email,
        'telephone'      => $client->telephone,
        'date_naissance' => $client->date_naissance,
        // pas de 'password'
    ]);

    $response->assertRedirect('/admin/utilisateur');
    expect(User::find($client->id)->nom)->toBe('NomModifie');
});

// ── Gestion des commandes (admin) ─────────────────────────

test('un admin peut voir la liste des commandes', function () {
    $admin  = createAdmin();
    $client = createUser(['email' => 'client2@example.com']);

    Commande::create([
        'user_id' => $client->id,
        'total'   => '59.99',
        'status'  => 'termine',
    ]);

    $response = $this->actingAs($admin)->get('/admin/commande');
    $response->assertStatus(200);
    $response->assertSee('59.99');
});

test('un admin peut supprimer une commande', function () {
    $admin  = createAdmin();
    $client = createUser(['email' => 'client3@example.com']);

    $commande = Commande::create([
        'user_id' => $client->id,
        'total'   => '25.00',
        'status'  => 'termine',
    ]);

    $this->actingAs($admin)->delete("/admin/commande/{$commande->id}");

    $this->assertDatabaseMissing('commandes', ['id' => $commande->id]);
});

// ── Profil utilisateur ────────────────────────────────────

test('un utilisateur peut modifier son profil', function () {
    $user = createUser(['email' => 'profil@example.com']);

    // Simuler la session user comme le fait AuthController
    session(['user' => ['id' => $user->id]]);

    $response = $this->actingAs($user)->post('/userInfo', [
        'nom'            => 'NouveauNom',
        'prenom'         => 'NouveauPrenom',
        'email'          => 'profil@example.com',
        'telephone'      => '0611223344',
        'date_naissance' => '1990-05-10',
    ]);

    $response->assertRedirect('/');
    expect(User::find($user->id)->nom)->toBe('NouveauNom');
});

test('la modification du profil utilise les données validées (pas brutes)', function () {
    $user = createUser(['email' => 'valide@example.com']);
    session(['user' => ['id' => $user->id]]);

    // Envoi avec un nom trop court (moins de 2 caractères)
    $response = $this->actingAs($user)->post('/userInfo', [
        'nom'            => 'A',
        'prenom'         => 'B',
        'email'          => 'valide@example.com',
        'telephone'      => '0600000000',
        'date_naissance' => '1990-01-01',
    ]);

    $response->assertSessionHasErrors(['nom', 'prenom']);
    expect(User::find($user->id)->nom)->not->toBe('A'); // base non modifiée
});
