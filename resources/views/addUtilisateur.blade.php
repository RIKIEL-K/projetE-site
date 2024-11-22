@extends('layouts.master');
@section('title','Ajouter un utilisateur');
@section('content')
<div class="">
    {{-- <h1 class="text-uppercase mb-3 fs-0" style="font-size:1.5em;">Ajouter un utilisateur</h1> --}}
</div>
<form action="{{route('admin.utilisateur.store')}}" method="POST">
    @csrf
    <div class="container w-50">
     <div class="row mb-4">
        <div class="col">
          <div class="form-outline">
            <input type="text" id="prenom" class="form-control" name="prenom" value="{{old('prenom')}}" />
            <label class="form-label" for="prenom">Prenom</label>
            <label class="form-label" for="prenom">
                @error('prenom')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </label>
          </div>
        </div>
        <div class="col">
          <div  class="form-outline">
            <input type="text" id="nom" class="form-control" name="nom" value="{{old('nom')}}" />
            <label class="form-label" for="nom">Nom</label>
            <label class="form-label" for="nom">
                @error('nom')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </label>
          </div>
        </div>
      </div>

      <div  class="form-outline mb-4">
        <input type="email" id="courriel" class="form-control" name="email" value="{{old('email')}}" />
        <label class="form-label" for="email">Courriel</label>
        <label class="form-label" for="email">
            @error('email')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </label>
      </div>
      <div  class="form-outline mb-4">
        <input type="text" id="telephone" class="form-control" name="telephone" value="{{old('telephone')}}"/>
        <label class="form-label" for="telephone">Telephone</label>
        <label class="form-label" for="telephone">
            @error('telephone')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </label>
      </div>
        <div  class="form-outline mb-4">
        <input type="date" id="date_naissance" class="form-control" name="date_naissance" value="{{old('date_naissance')}}" />
        <label class="form-label" for="date_naissance">Date de naissance</label>
        <label class="form-label" for="date_naissance">
            @error('date_naissance')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </label>
      </div>
      <div  class="form-outline mb-4">
        <input type="password" id="mdp" class="form-control" name="password" value="{{old('password')}}"/>
        <label class="form-label" for="password">Mot de passe</label>
        <label class="form-label" for="password">
            @error('password')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </label>
      </div>
      <div class="form-check form-switch mb-4">
        <input
            class="form-check-input"
            type="checkbox"
            role="switch"
            id="statut"
            name="statut"
            value="1"
            {{ old('statut', 1) ? 'checked' : '' }}
        >
        <label class="form-check-label" for="statut">Statut</label>
        @error('statut')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>


      <button  type="submit" class="btn btn-primary btn-block mb-4">Ajouter l'utilisateur</button>

      </div>
    </div>

    </form>
@endsection
