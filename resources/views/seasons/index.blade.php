@extends('main_layout')

@section('title')
Temporadas de {{$serie->serie_name}}
@endsection

@section('css')
<link rel="stylesheet" href="{{ URL::asset('static/css/bootstrap.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('static/css/index.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('static/css/index_seasons.css') }}" />
@endsection

@section('main')
<a href="/series/{{$serie->id}}/seasons/create" class="btn btn-primary btn-lg" id="add_season">+</a>
<section class="box">
  <div id="list_header">
    <h4 style="margin-top: 0;" class="box">TEMPORADAS DE THE BIG BANG THEORY</h4>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Imagem</th>
          <th>Nome da temporada</th>
          <th>Nota</th>
          <!-- <th>Nº episódios</th> -->
          <th>Progresso</th>
          <th>Opções</th>
        </tr>
      </thead>
      <tbody>
        <?php $count = 0; ?>
        @foreach($seasons as $season)
        <tr>
          <td id="status_background"><?php echo ++$count ?></td>
          <td>
            @if(!empty($serie->serie_image))
            <img alt="Imagem da série {{ $serie->serie_image }}" src="{{ URL::asset('static/images/uploads/' .$serie->serie_image) }}" />
            @else
            <img alt="Imagem da série {{ $serie->serie_image }}" src="{{ URL::asset('static/images/no_image.jpg') }}" />
            @endif
          </td>
          <td>{{ $serie->serie_name }} - {{ $season->season_number }}ª temporada</td>
          <td>{{ $season->season_score }}</td>
          <!-- <td id="td_episode_{{$season->id}}"><button id="episode_{{ $season->id }}" class="btn btn-link" onclick="addSelect({{ $season->id }})">0</button></td> -->
          <td>
            <form method="POST" action="/series/{{ $serie->id }}/seasons/{{ $season->id }}">
              @csrf
              <input name="op" value="minus" hidden>
              <button>
                {!! file_get_contents('static/icons/minus_circle.svg') !!}
              </button>
            </form>
            {{ $season->watchedEpisodes->watched_episodes_qt }}
            /
            {{ $season->watchedEpisodes->total_episodes_qt }}
            <form method="POST" action="/series/{{ $serie->id }}/seasons/{{ $season->id }}">
              @csrf
              <input name="op" value="plus" hidden>
              <button>
                {!! file_get_contents('static/icons/plus_circle.svg') !!}
              </button>
            </form>
          </td>
          <td>
            <button class="btn btn-primary btn-sm">{!! file_get_contents('static/icons/edit.svg') !!}</button>
            <form method="POST" action="/series/{{ $serie->id }}/seasons/{{ $season->id }}">
              @csrf
              @method('DELETE')
              <button onclick="return confirm('Tem certeza que deseja excluir a {{ addslashes($serie->serie_name) }} - {{ $season->season_number }}ª temporada?')" class="btn btn-danger btn-sm">
                {!! file_get_contents('static/icons/remove.svg') !!}
              </button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</section>

<script>
  function addSelect(id, range = 30) {
    var x = document.createElement('SELECT');
    x.setAttribute("id", `select_${id}`);
    document.getElementById(`td_episode_${id}`).appendChild(x);
    document.getElementById(`episode_${id}`).classList.add('invisible');

    for (let i = 0; i <= range; i++) {
      var z = document.createElement("option");
      z.setAttribute("value", i);
      var t = document.createTextNode(i);
      z.appendChild(t);
      document.getElementById(`select_${id}`).appendChild(z);
    }
  }

  function sendForm(serieId, seasonId, operation) {
    let formData = new FormData();
    const token = document.querySelector('input[name="_token"]').value;
    formData.append('_token', token);
    formData.append('operation', operation);
    const url = `/series/${serieId}/seasons/${seasonId}`;
    fetch(url, {
      body: formData,
      method: 'POST'
    });
  }
</script>
@endsection