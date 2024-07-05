@extends('layouts.app')

@section('content')

	@php
		$id = request()->route('user');
		$user = App\Models\User::find($id);
		
	@endphp

	<h1> {{ $user->name }}</h1>

	<div class="dettagli">
		<p>Dettagli</p>
		<p>Email: {{ $user->email }}</p>
		<p>Telefono: {{ $user->phone }}</p>
		<p>Indirizzo: {{ $user->address }}</p>
		<p>Progetti: 
			<ul>
				@foreach ($user->projects as $project)
					<li><a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a></li>
				@endforeach
			</ul>
		</p>
	</div>

@endsection