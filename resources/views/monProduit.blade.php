@extends('layouts.UserMaster')
@section('titre','Les Produits')
@section('content')

<section class="py-3">
    <div class="container px-2 px-lg-5 my-3">
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6">
                <img class="card-img-top mb-5 mb-md-0" src="{{$imagePath}}" alt="{{$produit->nom}}" />
            </div>
            <div class="col-md-6">
                <div class="small mb-1 text-muted">Ref. #{{$produit->id}}</div>
                <h1 class="display-5 fw-bolder">{{$produit->nom}}</h1>
                <div class="fs-5 mb-5">
                    <span><span>$</span>{{$produit->prix_unitaire}}</span>
                </div>
                <p class="lead">{{$produit->description}}</p>
                <div class="mb-4">
                    <h5>Caractéristiques :</h5>
                    <table class="table table-bordered mb-5">
                        <tbody>
                            @forelse ($produit->option as $option)
                                <tr>
                                    <td>{{ $option->nom }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Aucune option disponible pour ce produit.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <input class="form-control text-center" disabled id="inputQuantity" type="number"
                           value="{{$produit->quantite}}" style="max-width: 4rem" />

                    {{-- Formulaire POST sécurisé (CSRF + auth requis) --}}
                    <form action="{{route('addCart', $produit->id)}}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-dark flex-shrink-0">
                            <i class="bi-cart-fill me-1"></i>
                            Ajouter au panier
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
