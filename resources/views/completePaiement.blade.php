@extends('layouts.UserMaster')

@section('content')
    <div class="container mt-5">
        <div class="alert alert-success text-center">
            <h1>Paiement réussi !</h1>
            <p>Merci pour votre commande. Vous recevrez bientôt un email de confirmation.</p>
            <a href="{{ route('index') }}" class="btn btn-primary">Retour à l'accueil</a>
        </div>
    </div>
@endsection
