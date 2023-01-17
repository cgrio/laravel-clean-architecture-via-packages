@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="shadow-sm bg-white rounded-1 p-3">
            <ul class="list-inline" id="lista-clientes">

            </ul>
        </div>
    </div>
    <h3 class="pt-3">Peças publicitárias (12/05/2022 - 16/05/2022)</h3>
    @include('layouts.components.div-midias')
    <h3 class="pt-3">Peças (12/05/2022 - 16/05/2022)</h3>
    @include('layouts.components.div-midias')
    <h3 class="pt-3">Peças (12/05/2022 - 16/05/2022)</h3>
    @include('layouts.components.div-midias')
</div>
<script>
    let url;
    url = 'https://randomuser.me/api/?results=30';
    url = 'https://dummyapi.io/data/v1/user/60d0fe4f5311236168a109ca';



    function createNode(element) {
    return document.createElement(element);
}

function append(parent, el) {
  return parent.appendChild(el);
}

window.onload = function(e){
const ul = document.getElementById('lista-clientes');
const url = 'https://randomuser.me/api/?results=2';

fetch(url)
.then((resp) => resp.json())
.then(function(data) {
  let contas = data.results;
  return contas.map(function(conta_cliente) {
      console.log(conta_cliente);
    let li = createNode('li');
     li.classList.add("list-inline-item");
     li.classList.add("active");
     li.classList.add("p-1");
    let img = createNode('img');
    img.classList.add("rounded-circle");
    let span = createNode('span');
    span.classList.add('h6');
    span.classList.add('p-2');
    img.src = conta_cliente.picture.medium;
    span.innerHTML = `${conta_cliente.name.first} ${conta_cliente.name.last}`;
    append(li, img);
    append(li, span);
    append(ul, li);
  })
})
.catch(function(error) {
  console.log(error);
});
}
</script>
@endsection
