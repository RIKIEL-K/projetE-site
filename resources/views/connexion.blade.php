@extends('layouts.connexionPattern')

@section('content')
    <div class="container mt-5 w-25">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email">
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="password" id="password">
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Se connecter</button>
        </form>

        <form action="{{ route('sign') }}" method="GET" class="mb-3">
            <button type="submit" class="btn btn-outline-primary w-100">S'inscrire</button>
        </form>
    </div>
@endsection
