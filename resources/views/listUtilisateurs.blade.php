@extends('layouts.master')
@section('titre',"Listes des utilisateurs")
@section('content')

<div class="container rounded bg-white mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="text-uppercase mb-3 fs-1" style="font-size:1.5em;">Les utilisateurs</h1>
            <a href="{{route('admin.utilisateur.create')}}" class="btn btn-dark"><i class="bi bi-plus-circle"></i></a>
        </div>
    <table class="table">
        <thead>
            <tr>
            <th scope="col">Nom</th>
            <th scope="col">Prenom</th>
            <th scope="col">Date de naissance</th>
            <th scope="col">Email</th>
            <th scope="col">Telephone</th>
            <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($utilisateurs as $utilisateur)
            <tr>
                <th scope="row">{{$utilisateur->nom}}</th>
                <td>{{$utilisateur->prenom}}</td>
                <td>{{$utilisateur->date_naissance}}</td>
                <td>{{$utilisateur->email}}</td>
                <td>{{$utilisateur->telephone}}</td>
                <td><a href="{{route('admin.utilisateur.edit',$utilisateur->id)}}" class="btn btn-primary"><i class="bi bi-pencil-square"></i></a></td>
                <td>
                    <form action="{{ route('admin.utilisateur.destroy', $utilisateur->id) }}" method="POST">
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
