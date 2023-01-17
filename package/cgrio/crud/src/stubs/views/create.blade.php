@if(!request()->ajax())
@extends(!request()->ajax()?'layouts.app':'layouts.blank')
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
   <h2 class="text-center fonte-logo">{{("Add {{ModeloNomeSingularCaixaAlta}}")}}</h2>
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
      <form method="post" action="{{ route('{{ModeloNomePluralCaixaBaixa}}.store') }}">
              @csrf
         @include('{{CaminhoView}}.form')
         <div class="row justify-content-center">
                @if(!request()->ajax())
             <button type="submit" class="btn btn-primary">{{__("Gravar {{ModeloNomeSingularCaixaAlta}}")}}</button>
             @else
             <button type="button" id="enviar-modal" class="btn btn-primary">{{__("Criar {{ModeloNomeSingularCaixaAlta}}")}}</button>
             @endif
            </div>
      </form>
  </div>
</div>
</div>
@if(!request()->ajax())
@endsection
@endif
