@extends('layouts.master')
@section('content')

<div class="">
    <h1 class="mb-3 fs-0" style="font-size:1.5em;">Modifier une option</h1>
</div>
<form action="{{route('admin.option.update', $option->id)}}" method="POST">
    @csrf
    @method("PUT")
    <div class="container w-50">
     <div class="row mb-4">
        <div class="col">
          <div  class="form-outline">
            <input type="text" id="nom" class="form-control" name="nom" value="{{$option->nom}}" />
            <label class="form-label" for="nom">Nom de l'article</label>
            <label class="form-label" for="nom">
                @error('nom')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </label>
          </div>
        </div>
      </div>
      <button  type="submit" class="btn btn-primary btn-block mb-4">Modifier</button>

      </div>
    </div>
</div>
    </form>

@endsection
