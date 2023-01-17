@if(!request()->ajax())
@extends('layouts.app')

@section('content')
@endif
<style>
  .uper {
    margin-top: 40px;
  }
  </style>
  <div class="container">
  <div class="card uper">
  <div class="card-header">
   <h2 class="text-center fonte-logo">{{__(' Editar {{ModeloNomeSingularCaixaAlta}}')}}</h2>
  </div>
  <div class="card-body">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div><br />
    @endif
      <form method="post" action="{{ route('{{ModeloNomePluralCaixaBaixa}}.update', ${{ModeloNomeSingularCaixaBaixa}}->id) }}">
          <div class="form-group">
              @csrf
              @method('PATCH')
      @include('{{CaminhoView}}.form_full')
      <div class="row justify-content-center">

          <button type="submit" class="btn btn-primary">{{__("Atualizar {{ModeloNomeSingularCaixaAlta}}")}}</button>
        </div>
      </form>
  </div>
</div>
</div>
@if(!request()->ajax())
@endsection
@endif
