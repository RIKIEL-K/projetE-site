<?php

// =========================================================
// TESTS : Authentification
// Couvre : connexion, inscription, déconnexion, sécurité
// =========================================================

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// ── Page de connexion ─────────────────────────────────────

test('la page de connexion est accessible', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
    $response->assertSee('Se connecter');
});

test('un utilisateur déjà connecté ne voit pas la page de login', function () {
    $user = createUser();
    $response = $this->actingAs($user)->get('/login');
    $response->assertRedirect('/');
});

// ── Connexion réussie ─────────────────────────────────────

test('un utilisateur peut se connecter avec des identifiants valides', function () {
    $user = createUser(['email' => 'test@example.com']);

    $response = $this->post('/login', [
        'email'    => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect();
    $this->assertAuthenticated();
});

test('un admin est redirigé correctement après connexion', function () {
    $admin = createAdmin(['email' => 'boss@example.com']);

    $this->post('/login', [
        'email'    => 'boss@example.com',
        'password' => 'password123',
    ]);

    $this->assertAuthenticated();
    expect(auth()->user()->statut)->toBe(1);
});

// ── Connexion échouée ─────────────────────────────────────

test('connexion refusée avec un mauvais mot de passe', function () {
    createUser(['email' => 'test@example.com']);

    $response = $this->post('/login', [
        'email'    => 'test@example.com',
        'password' => 'mauvais_mdp',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('connexion refusée avec un email inexistant', function () {
    $response = $this->post('/login', [
        'email'    => 'fantome@example.com',
        'password' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('le champ email est obligatoire à la connexion', function () {
    $response = $this->post('/login', [
        'email'    => '',
        'password' => 'password123',
    ]);
    $response->assertSessionHasErrors('email');
});

test('le champ mot de passe est obligatoire à la connexion', function () {
    $response = $this->post('/login', [
        'email'    => 'test@example.com',
        'password' => '',
    ]);
    $response->assertSessionHasErrors('password');
});

// ── Inscription ───────────────────────────────────────────

test('la page d\'inscription est accessible', function () {
    $response = $this->get('/signIn');
    $response->assertStatus(200);
});

test('un utilisateur peut s\'inscrire avec des données valides', function () {
    $response = $this->post('/signIn', [
        'nom'            => 'Martin',
        'prenom'         => 'Paul',
        'email'          => 'paul@example.com',
        'telephone'      => '0612345678',
        'date_naissance' => '1995-06-15',
        'password'       => 'secret123',
    ]);

    $response->assertRedirect('/login');
    $this->assertDatabaseHas('users', ['email' => 'paul@example.com']);
});

test('le statut est forcé à 0 à l\'inscription (pas d\'admin par formulaire)', function () {
    $this->post('/signIn', [
        'nom'            => 'Hacker',
        'prenom'         => 'Evil',
        'email'          => 'hacker@example.com',
        'telephone'      => '0600000000',
        'date_naissance' => '1990-01-01',
        'password'       => 'hacked123',
        'statut'         => 1, // tentative d'élévation de privilèges
    ]);

    $user = User::where('email', 'hacker@example.com')->first();
    expect($user->statut)->toBe(0); // DOIT être 0 malgré la tentative
});

test('inscription refusée si l\'email est déjà utilisé', function () {
    createUser(['email' => 'existe@example.com']);

    $response = $this->post('/signIn', [
        'nom'            => 'Autre',
        'prenom'         => 'User',
        'email'          => 'existe@example.com',
        'telephone'      => '0600000000',
        'date_naissance' => '1990-01-01',
        'password'       => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
});

test('le mot de passe est hashé à l\'inscription', function () {
    $this->post('/signIn', [
        'nom'            => 'Secure',
        'prenom'         => 'User',
        'email'          => 'secure@example.com',
        'telephone'      => '0600000000',
        'date_naissance' => '1995-01-01',
        'password'       => 'monmotdepasse',
    ]);

    $user = User::where('email', 'secure@example.com')->first();
    expect(Hash::check('monmotdepasse', $user->password))->toBeTrue();
    expect($user->password)->not->toBe('monmotdepasse'); // jamais en clair
});

// ── Déconnexion ───────────────────────────────────────────

test('un utilisateur authentifié peut se déconnecter', function () {
    $user = createUser();

    $response = $this->actingAs($user)->delete('/logout');

    $response->assertRedirect('/login');
    $this->assertGuest();
});

test('le password n\'est pas stocké dans la session après connexion', function () {
    createUser(['email' => 'sessiontest@example.com']);

    $this->post('/login', [
        'email'    => 'sessiontest@example.com',
        'password' => 'password123',
    ]);

    $sessionUser = session('user');
    expect($sessionUser)->not->toHaveKey('password');
});
