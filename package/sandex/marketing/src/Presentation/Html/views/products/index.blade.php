@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-end">
        <div class="col-md-8 py-2">
        <span class="h3"> Products </span>
        </div>
        <div class="col-md-4 px-3 py-2">
<a href="/products/create" class="btn btn-sm btn-primary">New</a>
        </div>
    </div>
    @if(empty($data->original["data"]))
    <p>Ainda não existem produtos cadastrados</p>
    @else
    <div class="row justify-content-center">
        <div class="col h5"><b> id</b></div>
        <div class="col h5"><b> name</b></div>
        <div class="col h5"><b> description</b></div>
        <div class="col h5"><b> sku</b></div>
        <div class="col h5"><b> ncm</b></div>
        <div class="col h5"><b> created_at</b></div>
        <div class="col h5"><b> updated_at</b></div>
    </div>
    @foreach ($data->original["data"] as $product )
    <div class="row justify-content-center">
        <div class="col"> {{$product["id"]}}</div>
        <div class="col"> {{$product["name"]}}</div>
        <div class="col"> {{$product["description"]}}</div>
        <div class="col"> {{$product["sku"]}}</div>
        <div class="col"> {{$product["ncm"]}}</div>
        <div class="col"> {{\Carbon\Carbon::createFromTimeStamp(strtotime($product["created_at"]))->diffForHumans()}}</div>
        <div class="col"> {{\Carbon\Carbon::createFromTimeStamp(strtotime($product["updated_at"]))->diffForHumans()}}</div>
    </div>
    @endforeach



    @php
     $paginator = new Illuminate\Pagination\LengthAwarePaginator($data->original["links"], $data->original["total"], $data->original["per_page"],$data->original["current_page"],['path'=>'products']);


     @endphp
     {{$paginator->links()->withPath('/products')}}
    @endif
</div>
@endsection
