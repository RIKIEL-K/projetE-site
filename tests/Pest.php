<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

// Helper : creer un utilisateur simple
function createUser(array $attributes = []): \App\Models\User
{
    return \App\Models\User::create(array_merge([
        'nom'            => 'Dupont',
        'prenom'         => 'Jean',
        'email'          => 'jean@test.com',
        'telephone'      => '0601020304',
        'date_naissance' => '1990-01-01',
        'password'       => bcrypt('password123'),
        'statut'         => 0,
    ], $attributes));
}

// Helper : creer un administrateur
function createAdmin(array $attributes = []): \App\Models\User
{
    return createUser(array_merge([
        'email'  => 'admin@test.com',
        'statut' => 1,
    ], $attributes));
}

// Helper : creer un produit
function createProduit(array $attributes = []): \App\Models\Produit
{
    return \App\Models\Produit::create(array_merge([
        'nom'          => 'Produit Test',
        'description'  => 'Description du produit test',
        'quantite'     => 10,
        'prix_unitaire' => '29.99',
        'sold'         => false,
    ], $attributes));
}
