@extends('layouts.UserMaster')
@section('titre','Les Produits')
@section('content')

<form action="" method="POST">
    @csrf
    <section class="py-3">
        <div class="container px-2 px-lg-5 my-3">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="..." /></div>
                <div class="col-md-6">
                    <div class="small mb-1">{{$produit->id}}</div>
                    <h1 class="display-5 fw-bolder">{{$produit->nom}}</h1>
                    <div class="fs-5 mb-5">
                        <span><span>$</span>{{$produit->prix_unitaire}}</span>
                    </div>
                    <p class="lead">{{$produit->description}}</p>
                    <div class="mb-4">
                        <h5>caractéristiques :</h5>
                        <table class="table table-bordered mb-5">
                            <tbody>
                                @forelse ($produit->option as $key => $option)
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

                    <div class="d-flex">
                        <input class="form-control text-center me-3" disabled id="inputQuantity" type="num" value="{{$produit->quantite}}" style="max-width: 3rem" />
                        <button class="btn btn-outline-dark flex-shrink-0" type="button">
                            <i class="bi-cart-fill me-1"></i>
                            Add to cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>

@endsection
