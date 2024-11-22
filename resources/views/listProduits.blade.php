@extends('layouts.master')
@section('titre','Les Produits')
@section('content')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="text-uppercase mb-3 fs-1" style="font-size:1.5em;">Les Produits</h1>
    <a href="{{route('admin.produit.create')}}" class="btn btn-dark"><i class="bi bi-plus-circle"></i></a>
</div>
<table class="table table-striped-columns">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nom</th>
        <th scope="col">prix unitaire</th>
        <th scope="col">quantité</th>
        <th scope="col">description</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($produits as $produit)
        <tr>
            <th scope="row">{{$produit->id}}</th>
            <td>{{$produit->nom}}</td>
            <td>{{$produit->prix_unitaire}}</td>
            <td>{{$produit->quantite}}</td>
            <td>{{$produit->description}}</td>
            <td><a href="{{route('admin.produit.edit',$produit->id)}}" class="btn btn-primary"><i class="bi bi-pencil-square"></i></a></td>
            <td>
                <form action="{{ route('admin.produit.destroy', $produit->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash3"></i></button>
                </form>
            </td>
          </tr>
        @endforeach

    </tbody>
  </table>

@endsection
