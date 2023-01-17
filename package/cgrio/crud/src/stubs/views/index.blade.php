@if(!request()->ajax())
@extends(!request()->ajax()?'layouts.app':'layouts.blank')
@section('css')
@endif
<style>
    .loading {
        background: lightgrey;
        padding: 15px;
        position: fixed;
        border-radius: 4px;
        left: 50%;
        top: 50%;
        text-align: center;
        margin: -40px 0 0 -50px;
        z-index: 2000;
        display: none;
    }

    /*  */

    .form-group.required label:after {
        content: " *";
        color: red;
        font-weight: bold;
    }

    .uper {
        margin-top: 40px;
    }
</style>
@if(!request()->ajax())
@endsection
@section('content')
@endif
<div id="content" class="container-fluid">
        @include('layouts.modal')

    <div class="uper">
        @include('layouts.alerts')

        <h4>{{__("{{ModeloNomeSingularCaixaAlta}}s")}}</h4>
        <div class="row p-2">
            <div class="col-md-12">
                <div class="float-left">
                    <a href="{{url('{{ModeloNomePluralCaixaBaixa}}/create')}}" class="btn btn-sm btn-primary">{{__('New')}}</a>
                    <a href="#modalForm" data-href="{{url('/{{ModeloNomePluralCaixaBaixa}}/create')}}" data-toggle="modal" class="lmodal btn btn-sm btn-success ">New</a> </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5> <a data-toggle="collapse" href="#{{ModeloNomeSingularCaixaBaixa}}_filtros" role="button" aria-expanded="false" aria-controls="{{ModeloNomeSingularCaixaBaixa}}_filtros">Filtros</a></h5>
            </div>

            <div class="card-body {{request()->session()->has('{{ModeloNomeSingularCaixaBaixa}}_busca')?'show':'collapse'}}  multi-collapse" id="{{ModeloNomeSingularCaixaBaixa}}_filtros">
                <div class="container-fluid">

                    <form action="/{{ModeloNomePluralCaixaBaixa}}" id="form_search_{{ModeloNomeSingularCaixaBaixa}}" class="form-inline" method="get">
                        @csrf
                        {{CamposPesquisa}}
                         <div class="row">
                            <div class="col-md-12">


                                <input type="submit" class="btn btn-sm btn-outline btn-primary m-1" value="{{ __('Buscar') }}">
                                <a class="btn btn-sm btn-outline btn-primary m-1" href="/{{ModeloNomePluralCaixaBaixa}}/limpar_busca">Limpar</a>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row m-1 mt-5">
            <div class="col-md-12">
                <div class="">
                    <table class="table table-sm table-unstyled">
                        <tr>
                                {{CabecalhoTabela}}
                            <td colspan="2">{{__('Action')}}</td>
                        </tr>

                        </thead>
                        <tbody>
                            @foreach(${{ModeloNomePluralCaixaBaixa}} as ${{ModeloNomeSingularCaixaBaixa}})
                            <tr>
                                {{CamposTabela}}
                                <td><a href="{{ route('{{ModeloNomePluralCaixaBaixa}}.edit',${{ModeloNomeSingularCaixaBaixa}}->{{ChavePrimaria}})}}" class="btn btn-sm btn-primary">Edit</a></td>
                                <td>
                                    <form action="{{ route('{{ModeloNomePluralCaixaBaixa}}.destroy', ${{ModeloNomeSingularCaixaBaixa}}->{{ChavePrimaria}})}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <nav>
                    <ul class="pagination justify-content-center">
                        {{${{ModeloNomePluralCaixaBaixa}}->links('vendor.pagination.bootstrap-4')}}
                    </ul>
                </nav>

            </div>
        </div>
    </div>

    @include('layouts.load')

    @if(!request()->ajax())
    @endsection
    @section('script')

    <script src="{{asset('js/modal-form.js')}}"></script>
    <script src="{{asset('js/slugify.js')}}"></script>
    <script src="https://use.fontawesome.com/2c7a93b259.js"></script>
    <script>
        $(document).on('blur', '.pesquisa', function(e) {
            if ($(this).val() != '') {
                console.log($(this));
                var form = $(this.form);
                console.log(form);
                console.log(form.attr('id'));
                form.submit();
            }
        });
    </script>
    @endsection
    @endif
