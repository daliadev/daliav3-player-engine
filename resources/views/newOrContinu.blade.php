@extends('layouts.app')

@section('content')

  <div class="col-lg-offset-4 col-lg-4 col-sm-offset-3 col-sm-6">
    <br>
		<div class="panel panel-primary">
			<div class="panel-heading">
        Il semblerait que vous ayez deja commencé cette activité.<br>
      </div>
			<div class="panel-body">
				<p>Que voulez vous faire ?</p>
        {!! link_to_route('activite.new', 'Recommencer cette activité', [$activite_id], ['class' => 'btn btn-info pull-right']) !!}
        {!! link_to_route('activite.showScene', 'Reprendre là où je me suis arreté', [$activite_id], ['class' => 'btn btn-info pull-left']) !!}
      </div>
		</div>
  </div>

@stop
