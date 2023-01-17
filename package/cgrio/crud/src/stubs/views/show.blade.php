@extends('layouts.app')
@section('content')
<div class="container">
        <div class="card">
                <div class="card-header">
                    <h2 class="fonte-logo text-center">{{__('{{ModeloNomeSingularCaixaAlta}}')}} ({{${{ModeloNomeSingularCaixaBaixa}}->id}})</h2>
                </div>

                <div class="card-body">
                    <div class="container-fluid">
                        {{CamposView}}
                    <div class="row">
                <div class="col-md-12 text-center">
<div class="row justify-content-center">

    <a href="{{ route('{{ModeloNomePluralCaixaBaixa}}.edit',${{ModeloNomeSingularCaixaBaixa}}->id)}}" class="btn btn-primary m-1">Edit</a>
    <form action="{{ route('{{ModeloNomePluralCaixaBaixa}}.destroy', ${{ModeloNomeSingularCaixaBaixa}}->id)}}" method="post">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger m-1" type="submit">Delete</button>
    </form>

</div>
            </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
