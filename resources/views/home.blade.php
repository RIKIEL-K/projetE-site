@extends('layouts.UserMaster')
@section('titre','Les Produits')
@section('content')

<div class="bg-primary text-white py-5">
    <div class="container">
      <h1>
        Meilleurs produits & <br />
        marque dans notre store
      </h1>
    </div>
  </div>
  <!-- Jumbotron -->
</header>
<!-- Products -->
<section>
  <div class="container my-5">
    <header class="mb-4">
      <h3>Nouveaux Produits</h3>
    </header>
    <div class="row">
        @foreach ($produits as $produit)
          <div class="col-lg-3 col-md-6 col-sm-6 d-flex">
            <div class="card w-100 my-2 shadow-2-strong">

              @if ($produit->image)
                <img src="{{ asset('storage/' . $produit->image->image_path) }}" class="card-img-top"/>
              @else
                <img src="{{ asset('images/no-image.png') }}" class="card-img-top"/>
              @endif


              <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $produit->nom }}</h5>
                <p class="card-text">{{ $produit->prix_unitaire }} <span> $</span></p>


                <div class="d-flex align-items-end pt-3 px-0 pb-0 mt-auto">

                  <a href="{{ route('addCart', $produit->id) }}" class="btn btn-secondary me-1">
                    <i class="bi bi-cart-plus"></i>
                  </a>

                  <a href="{{ route('produit.show', $produit->id) }}" class="btn btn-secondary shadow-0 me-1">
                    <i class="bi bi-eye"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

  </div>
</section>
<!-- Products -->

@endsection
