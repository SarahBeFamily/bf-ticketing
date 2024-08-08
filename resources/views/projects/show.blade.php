@extends('layouts.app')

@section('content')

	@php
		$id = request()->route('project');
		$project = App\Models\Project::find($id);
		$customer = App\Models\Company::find($project->company_id) ?? null;
		$user = App\Models\User::find($project->assigned_to) ?? null;
	@endphp

	<h1> {{ $project->name }}</h1>
	<p>{{ $project->description }}</p>

	<div class="dettagli">
		<p>Dettagli</p>
		<p>Data di inizio: {{ date('d/m/Y', strtotime($project->started_at)) }}</p>
		<p>Scadenza: {{ date('d/m/Y', strtotime($project->deadline)) }}</p>
		<p>Stato: {{ $project->status }}</p>
		<p>Reparto: {{ $project->division }}</p>
		<p>Referente: 
			@if (!$user)
				Non assegnato <br>
				<a href="{{ route('projects.edit', $id) }}" class="button">Assegna ora</a>
			@else
				@foreach ($user as $team)
					<a href="{{ route('team.show', $team->id) }}">{{ $team->name }}</a>
				@endforeach
			@endif
		</p>
		<p>Azienda: 
			@if (!$customer)
				Non assegnata <br>
				<a href="{{ route('projects.edit', $id) }}" class="button">Assegna ora</a>
			@else
				<a href="{{ route('companies.show', $project->company_id) }}">{{ $customer->name }}</a>
			@endif
		</p>
		@if ($customer)
			<p>Clienti assegnati:
				@php
					$workers = $customer->workers ? json_decode($customer->workers) : [];
				@endphp
				@if (count($workers) == 0)
					Nessun cliente assegnato<br>
					<a href="{{ route('companies.edit', $project->company_id) }}" class="button">Assegna ora</a>
				@else
					@foreach ($workers as $worker)
						@php
							$worker = App\Models\User::find($worker);
							$worker_company = App\Models\Company::find($worker->company_id);
							$company_name = $worker_company ? '('.$worker_company->name.')' : '';
						@endphp
						<a href="{{ route('profile.edit', $worker->id) }}">{{ $worker->name }} {{ $company_name }}</a>
					@endforeach
				@endif
			</p>
		@endif
	</div>
	
	@can('edit users')
	<div class="actions">
		
		<a href="{{ route('projects.edit', $id) }}" class="button btn-primary">Modifica</a>

		<form action="{{ route('projects.destroy', $id) }}" method="POST">
			@csrf
			@method('DELETE')
			<button type="submit" class="button btn-primary">Elimina</button>
		</form>
	</div>
	@endcan

@endsection