@extends('layouts.master')
@section('titre',"Listes des utilisateurs")
@section('content')

<div class="container rounded bg-white mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="text-uppercase mb-3 fs-1" style="font-size:1.5em;">Les Commandes</h1>
        </div>
    <table class="table">
        <thead>
            <tr>
            <th scope="col">ID</th>
            <th scope="col">utilisateur</th>
            <th scope="col">Produits commandés</th>
            <th scope="col">Total</th>
            <th scope="col">Status</th>
            <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($commandes as $commande)
            <tr>
                <th scope="row">{{$commande->id}}</th>
                <td>{{$commande->user->nom}}</td>
                <td>
                    <ul>
                        @foreach ($commande->items as $item)
                            <li> {{ $item->quantite }} {{ $item->produit->nom }} à {{ $item->prix }}$ la pièce</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{$commande->total}}</td>
                <td><span class="badge rounded-pill text-bg-primary {{ $commande->status  == 'echoué' ? 'text-bg-danger' : 'text-bg-primary' }}">{{$commande->status}}</span></td>

                <td>
                    <form action="{{ route('commande.supprimer', $commande->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash3"></i></button>
                    </form>
                </td>
                </tr>
            @endforeach

        </tbody>
    </table>
 </div>

@endsection
