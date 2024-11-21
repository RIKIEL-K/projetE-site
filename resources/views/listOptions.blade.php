@extends('layouts.master')
@section('titre','Les Produits')
@section('content')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="text-uppercase mb-3 fs-1" style="font-size:1.5em;">Les Options</h1>
    <a href="{{route('admin.option.create')}}" class="btn btn-dark">Ajouter une option</a>
</div>
<table class="table table-striped-columns">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nom</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($options as $option)
        <tr>
            <th scope="row">{{$option->id}}</th>
            <td>{{$option->nom}}</td>
            <td><a href="{{route('admin.option.edit',$option->id)}}" class="btn btn-primary">Editer</a></td>
            <td>
                <form action="{{ route('admin.option.destroy', $option->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                </form>
            </td>
          </tr>
        @endforeach

    </tbody>
  </table>

@endsection
