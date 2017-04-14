@extends('layouts.app')

@section('content')
    <br>
    <div class="col-sm-offset-4 col-sm-4">
    	@if(session()->has('ok'))
			   <div class="alert alert-success alert-dismissible">{!! session('ok') !!}</div>
		  @endif

    <!-- Table liste des jeux : consultation et ajout-->
		<div id="view-box" class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Liste des activités</h3>
			</div>
      <div class="panel-body">
        <table class="table">
        	<thead>
        		<tr>
        			<th>Id_activite</th>
              <th>Nom_activite</th>
        			<th></th>
        			<th></th>
        			<th></th>
        		</tr>
        	</thead>
        	<tbody>
        		@foreach ($activites as $activite)
        			<tr>
        				<td><strong>{!! link_to_route('activite.show', $activite->id, [$activite->id]) !!}</strong></td>
                <td>{!! $activite->name !!}</td>
        				<td></td>
        				<td></td>
        				<td></td>
        			</tr>
        		@endforeach
        		</tbody>
        </table>
      </div>
    </div>
  {!! link_to_route('activite.create', 'Creer une activité', [], ['class' => 'btn btn-info pull-right']) !!}
  {!! $links !!}
</div>
@stop
