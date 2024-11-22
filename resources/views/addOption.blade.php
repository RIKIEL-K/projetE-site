@extends('layouts.master')
@section('content')

<div class="">
    {{-- <h1 class="mb-3 fs-0" style="font-size:1.5em;">Ajouter une option</h1> --}}
</div>
<form action="{{route('admin.option.store')}}" method="POST">
    @csrf
    <div class="container w-50">
     <div class="row mb-4">
        <div class="col">
          <div class="form-outline">
            <input type="text" id="nom" class="form-control" name="nom" value="{{old('nom')}}" />
            <label class="form-label" for="nom">Nom de l'option</label>
            <label class="form-label" for="nom">
                @error('nom')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </label>
          </div>
        </div>
    </div>
      <button  type="submit" class="btn btn-primary btn-block mb-4">creer l'option</button>
      </div>
    </div>

    </form>

@endsection
