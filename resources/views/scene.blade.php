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
				<p><u>Scene en cours :</u> {!! $active_scene[0]->NOM_SCENE_NAME !!}</p>
        <p><u>Position de la scene :</u> {!! $position !!}e</p>
        <p><u>Id de la scene :</u> {!! $active_scene[0]->ID_SCENE !!}</p>
        @if($active_scene[0]->SCORABLE == 1)
          <p><u>Exercice :</u> Contenu de l'exercice </p>
        @endif
      </div>
		</div>
    
    @if($last_scene)
      {!! link_to_route('result.show', 'Voir les resultats', [$activite_id], ['class' => 'btn btn-info pull-right']) !!}
    @endif
    @if($last_scene == 0)
      {!! link_to_route('activite.next', 'Suite', [$activite_id], ['class' => 'btn btn-info pull-right']) !!}
    @endif

  </div>

@stop
