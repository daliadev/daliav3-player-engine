@extends('layouts.app')

@section('content')

  <div class="col-lg-offset-4 col-lg-4 col-sm-offset-3 col-sm-6">
    <br>
		<div class="panel panel-primary">
			<div class="panel-heading">
        Résultats de l'activité :
      </div>
			<div class="panel-body">
				<p><u>Nombre de scenes :</u></p>
        <p><u>Scores :</u></p>
      </div>
		</div>

    {!! link_to_route('activite.index', 'Retour au menu', [], ['class' => 'btn btn-info pull-right']) !!}

  </div>

@stop