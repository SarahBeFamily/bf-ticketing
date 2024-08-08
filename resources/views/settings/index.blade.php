@extends('layouts.app')

@section('content')

@can('edit users', App\Models\User::class)

	@php
		$division = $settings->where('key', 'division')->first() ?? '';
		if ($division) {
			$division = json_decode( $division->value );
			$division = implode("\n", $division);
		}
		$projectStatus = $settings->where('key', 'project_status')->first() ?? '';
		if ($projectStatus) {
			$projectStatus = json_decode( $projectStatus->value );
			$projectStatus = implode("\n", $projectStatus);
		}
		$ticket_types = $settings->where('key', 'ticket_type')->first() ?? '';
		if ($ticket_types) {
			$ticket_types = json_decode( $ticket_type->value );
			$ticket_types = implode("\n", $ticket_type);
		}
		// echo '<pre>';
		// var_dump($projectStatus);
		// echo '</pre>';
	@endphp
	
	<div class="messages">
		@if ($errors->any())
			<ul>
				@foreach ($errors->all() as $error)
					<li class="alert alert-error">{{ $error }}</li>
				@endforeach
			</ul>
		@endif

		@if (session('success'))
			<div class="alert alert-success">{{ session('success') }}</div>
		@endif
	</div>

	<h1>Impostazioni di sistema</h1>
	<p class="w-3/4 mt-4">
		In quest'area è possibile modificare le impostazioni del sistema.<br>
		Aggiungere reparti, modificare i permessi degli utenti, gestire i ruoli e tanto altro.
	</p>

	<form action="{{ route('settings.update') }}" method="POST" class="w-3/4">
		@csrf
		
		<div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

			<div class="form-group sm:col-span-3">
				<label for="division" class="block text-sm font-medium leading-6 text-gray-900">Reparti</label>
				<textarea name="division" id="division" cols="30" rows="10" required>{{ $division }}</textarea>
				<p>Inserisci o modifica i reparti inserendoli uno per riga</p>
			</div>

			<div class="form-group sm:col-span-3">
				<label for="project-status" class="block text-sm font-medium leading-6 text-gray-900">Stati Progetto</label>
				<textarea name="project_status" id="project-status" cols="30" rows="10" required>{{ $projectStatus }}</textarea>
				<p>Inserisci o modifica gli stati per i progetti inserendoli uno per riga</p>
			</div>

			<div class="form-group sm:col-span-3">
				<label for="ticket-type" class="block text-sm font-medium leading-6 text-gray-900">Tipologie Ticket</label>
				<textarea name="ticket_type" id="ticket-type" cols="30" rows="10" required>{{ $ticket_types }}</textarea>
				<p>Inserisci o modifica le tipologie di tickets inserendole una per riga</p>
			</div>

		</div>

		<button type="submit" class="button btn-primary my-6">Salva</button>
	</form>
@else
	<p>Non hai i permessi per accedere a questa pagina</p>
@endcan

@endsection