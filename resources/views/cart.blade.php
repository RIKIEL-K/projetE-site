@extends('layouts.UserMaster')
@section('content')

<section class="my-5">
    <div class="container">
        <div class="row">
            <!-- Cart -->
            <div class="col-lg-9">
                <div class="card border shadow-0">
                    <div class="m-4">
                        <h4 class="card-title mb-4">Votre Panier</h4>
                        @foreach($cart as $id => $item)
                        <div class="row gy-3 mb-4 align-items-center">
                            <div class="col-3">
                                <img src="{{$item['image']}}" class="border rounded me-3" style="width: 96px; height: 96px" />
                                <p>{{ $item['name'] }}</p>
                            </div>
                            <div class="col-3">
                                <p class="fw-bold mb-0 me-2 pe-2">{{ $item['prix'] }} €</p>
                            </div>
                            <div class="col-3">
                                <form action="{{ route('cart.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $id }}">
                                    <input type="number" name="qte" class="form-control" value="{{ $item['qte'] }}" min="1" />
                            </div>
                            <div class="col-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-clockwise"></i></button>
                                </form>
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="border-top pt-4 mx-4 mb-4">
                        <p><i class="fas fa-truck text-muted fa-lg"></i> Livraison gratuite en 1 à 2 semaines</p>
                    </div>
                </div>
            </div>
            <!-- Cart -->

            <!-- Summary -->
            <div class="col-lg-3">
                <div class="card shadow-0 border">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <p class="mb-2">Prix total:</p>
                            <p class="mb-2">{{ $total }} €</p>
                        </div>
                        <div class="mt-3">
                            <form action="" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 shadow-0 mb-2"><i class="bi bi-credit-card-fill"></i></button>
                            </form>
                            <a href="{{route('index')}}" class="btn btn-light w-100 border mt-2"><i class="bi bi-arrow-return-left"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Summary -->
        </div>
    </div>
</section>

@endsection

