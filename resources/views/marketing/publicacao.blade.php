@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="shadow-sm bg-white rounded-1 p-3">

            <ul class="list-inline" id="lista-clientes">

                <li class="list-inline-item active p-1"><img class="rounded-circle"
                        src="https://randomuser.me/api/portraits/med/men/43.jpg"><span class="h6 p-2">Armin
                        Berger</span></li>

            </ul>

        </div>
    </div>

    <div class="row bg-white p-5">
        <div class="col-2 col-lg-4 col-sm-12">
            @include('layouts.components.card-midias',['imagem'=> '/img/anuncios/21.webp'])
        </div>
        <div class="col-2 col-lg-8 col-sm-12">

            <div class="row">
                <div class="col-12">
                    <h3><i class="bi bi-facebook"></i> Facebook</h3>
                </div>
            </div>

        </div>
    </div>


</div>

@endsection
