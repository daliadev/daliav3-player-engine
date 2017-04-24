@extends('layouts.app')

@section('content')

  <div class="col-lg-offset-4 col-lg-4 col-sm-offset-3 col-sm-6">
    <br>
		<div class="panel panel-primary">
			<div class="panel-heading">
        Résumé de l'activité<br>
        <u>Scene :</u> {!! $scenes_list !!}<br>
      </div>
			<div class="panel-body">
				<p><u>Scene en cours :</u> {!! $active_scene[0]->name !!}</p>
        <p><u>Position de la scene :</u> {!! $position !!}e</p>
        <p><u>Id de la scene :</u> {!! $active_scene[0]->id !!}</p>
      </div>
		</div>
    <!-- Si isFinished => bouton checkResult -->
    @if($last_scene)
    <!-- A FAIRE : remplacer le "activite.next" ci dessous par une route "view result" -->
      {!! link_to_route('activite.result', 'Voir les resultats', [$activite_id], ['class' => 'btn btn-info pull-right']) !!}
    @endif
    @if($last_scene == 0)
      {!! link_to_route('activite.next', 'Suite', [$activite_id], ['class' => 'btn btn-info pull-right']) !!}
    @endif
    @if($first_scene == 0)
      {!! link_to_route('activite.previous', 'Retour', [$activite_id], ['class' => 'btn btn-info pull-left']) !!}
    @endif

  </div>

@stop
