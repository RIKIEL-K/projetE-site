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

                        @if(empty($cart))
                            <div class="alert alert-info text-center my-4">
                                <i class="bi bi-cart-x fs-3 d-block mb-2"></i>
                                Votre panier est vide.
                                <a href="{{ route('index') }}" class="d-block mt-2 btn btn-primary btn-sm">Voir nos produits</a>
                            </div>
                        @else
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

                            <div class="border-top pt-4 mx-4 mb-4">
                                <p><i class="fas fa-truck text-muted fa-lg"></i> Livraison gratuite en 1 a 2 semaines</p>
                            </div>
                        @endif
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
                            @if(!empty($cart))
                                <div id="paypal-button-container" class="mt-3"></div>
                            @endif
                            <a href="{{route('index')}}" class="btn btn-light w-100 border mt-2"><i class="bi bi-arrow-return-left"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Summary -->
        </div>
    </div>

</section>

@if(!empty($cart))
{{-- SDK PayPal charge uniquement sur la page panier, pas sur toutes les pages --}}
<script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency=CAD"></script>
<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        // Le total affiche est calcule cote serveur, aucune manipulation possible
                        value: '{{ number_format($total, 2, '.', '') }}'
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                // Redirection sans passer le total en parametre URL
                window.location.href = "{{ route('commande.OnStoreCompleted') }}";
            });
        },
        onCancel: function(data) {
            window.location.href = "{{ route('commande.OnStoreCancelled') }}";
        },
        onError: function(err) {
            console.error('Erreur PayPal:', err);
            alert('Une erreur est survenue lors du paiement. Veuillez reessayer.');
        }
    }).render('#paypal-button-container');
</script>
@endif

@endsection
