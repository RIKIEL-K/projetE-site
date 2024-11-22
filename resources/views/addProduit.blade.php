@extends('layouts.master')
@section('content')

<div class="">
    {{-- <h1 class="mb-3 fs-0" style="font-size:1.5em;">Ajouter un produit</h1> --}}
</div>
<form action="{{route('admin.produit.store')}}" method="POST">
    @csrf
    <div class="container w-50">
     <div class="row mb-4">
        <div class="col">
          <div class="form-outline">
            <input type="text" id="nom" class="form-control" name="nom" value="{{old('nom')}}" />
            <label class="form-label" for="nom">Nom de l'article</label>
            <label class="form-label" for="nom">
                @error('nom')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </label>
          </div>
        </div>
    </div>
        <div class="mb-4">
            <div class="form-floating">
                <textarea class="form-control" name="description" id="description">{{old('description')}}</textarea>
                <label class="form-label" for="description">
                    @error('description')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </label>
            </div>
        </div>
      <div class="form-outline mb-4">
        <input type="number" id="quantite" class="form-control" name="quantite" value="{{old('quantite')}}"/>
        <label class="form-label" for="quantite">Quantité</label>
        <label class="form-label" for="quantite">
            @error('quantite')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </label>
      </div>
      <div  class="form-outline mb-4">
        <input type="text" id="prix_unitaire" class="form-control" name="prix_unitaire" value="{{old('prix_unitaire')}}"/>
        <label class="form-label" for="prix_unitaire">Prix Unitaire</label>
        <label class="form-label" for="prix_unitaire">
            @error('prix_unitaire')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </label>
      </div>
      <div class="form-check form-switch mb-4">
        <input
            class="form-check-input"
            type="checkbox"
            role="switch"
            id="sold"
            name="sold"
            value="1"
            {{ old('sold', 1) ? 'checked' : '' }}
        >
        <label class="form-check-label" for="sold">vendu ?</label>
        @error('sold')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <!-- Ajout du champ select pour les options -->
    <div class="form-outline mb-4">
        <label for="options" class="form-label">Options :</label>
        <select name="options[]" id="options" class="form-select" multiple>
            @foreach ($options as $option)
                <option value="{{ $option->id }}">{{ $option->nom }}</option>
            @endforeach
        </select>
        @error('options')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <!-- Fin du champ select -->
      <button  type="submit" class="btn btn-primary btn-block mb-4">Ajouter le produit</button>

      </div>
    </div>

    </form>

@endsection
