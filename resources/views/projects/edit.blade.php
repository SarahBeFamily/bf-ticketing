@extends('layouts.app')

@section('content')

@php
	$data_inizio = new DateTime($project->started_at);
	$data_inizio = $data_inizio->format('Y-m-d');
	$scadenza = new DateTime($project->deadline);
	$scadenza = $scadenza->format('Y-m-d');
	$divisions = json_decode( $settings->get('division'), true );
	$projectStatus = json_decode( $settings->get('project_status'), true );
	// dd($settings->get('division'));
	// var_dump($project->division);
@endphp

<div class="messages">
	@if (session('success'))
		<div class="alert alert-success">
			{{ session('success') }}
		</div>
	@elseif (session('error'))
		<div class="alert alert-error">
			{{ session('error') }}
		</div>
	@elseif (session('status') === 'project-updated')
		<div class="alert alert-success">
			{{ __('Salvato.') }}
		</div>
	@elseif ($errors->any())
		<div class="alert alert-error">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
</div>

	<span class="mb-2">Modifica progetto</span>
	<h1>{{ $project->name }}</h1>

	<p class="sm:col-span-5">Modifica le informazioni del progetto</p>
	
	<form action="{{ route('projects.update', $project->id) }}" method="POST">
		@csrf
		@method('PATCH')
		
		<div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8">
			<div class="sm:col-span-full">
				<label for="name" class="block text-sm font-medium leading-6 text-gray-900">Titolo</label>
				<input 
					type="text" 
					name="name" 
					id="name" 
					class="block w-full text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" 
					value="{{ $project->name }}">
			</div>
			
			<div class="sm:col-span-full">
				<label for="description" class="block text-sm font-medium leading-6 text-gray-900">Descrizione</label>
				<textarea 
					name="description" 
					id="description" 
					class="block w-full text-gray-900 ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">{{ $project->description }}</textarea>
			</div>

			<div class="sm:col-span-full md:col-span-1">
				<label for="started_at" class="block text-sm font-medium leading-6 text-gray-900">Data di inizio</label>
				<input type="date" name="started_at" id="started_at" class="form-control block w-full" value="{{ $data_inizio }}">
			</div>

			<div class="sm:col-span-full md:col-span-1">
				<label for="deadline" class="block text-sm font-medium leading-6 text-gray-900">Scadenza</label>
				<input type="date" name="deadline" id="deadline" class="form-control block w-full" value="{{ $scadenza ?? old('deadline') }}">
			</div>

			<div class="sm:col-span-full md:col-span-1">
				<label for="division" class="block text-sm font-medium leading-6 text-gray-900">Reparto</label>
				<select name="division" id="division" class="form-control block w-full">
					@if (is_array($divisions))
						@foreach ($divisions as $division)
							<option value="{{ $division }}" @selected($project->division == $division)>{{ $division }}</option>
						@endforeach
					@else
						<option value="{{ $project->division }}">{{ $project->division }}</option>
					@endif
				</select>
			</div>

			<div class="sm:col-span-full md:col-span-1">
				<label for="status" class="block text-sm font-medium leading-6 text-gray-900">Stato</label>
				<select name="status" id="status" class="form-control block w-full">
					@if (is_array($projectStatus))
						@foreach ($projectStatus as $status)
							<option value="{{ $status }}" @selected($project->status == $status)>{{ $status }}</option>
						@endforeach
					@else
						<option value="{{ $project->status }}">{{ $project->status }}</option>
					@endif
				</select>
			</div>

			<div class="sm:col-span-full md:col-span-2">
				<label for="team" class="block text-sm font-medium leading-6 text-gray-900">Referenti</label>
				<select name="assigned_to[]" id="team" class="form-control block w-full" multiple>
					@foreach ($team as $member)
						<option value="{{ $member->id }}" @selected(in_array($member->id, $project->assigned_to))>{{ $member->name }}</option>
					@endforeach
				</select>
			</div>

			<div class="sm:col-span-full md:col-span-2">
				<label for="customer" class="block text-sm font-medium leading-6 text-gray-900">Assegna ad Azienda</label>
				<select name="company_id" id="customer" class="block w-full text-gray-900 ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
					<option value="">Scegli</option>
					@foreach ($companies as $company)
						<option value="{{ $company->id }}" @selected($project->company_id == $company->id)>{{ $company->name }}</option>
					@endforeach
				</select>
			</div>
		</div>

		<button type="submit" class="button inline-flex items-center rounded-md bg-accent-100 px-3 py-2 my-6 text-sm font-medium text-white shadow-lg ring-1 ring-inset ring-gray-300 hover:bg-secondary transition-all">Salva</button>
		@if (session('status') === 'project-updated')
			<p
				x-data="{ show: true }"
				x-show="show"
				x-transition
				x-init="setTimeout(() => show = false, 2000)"
				class="text-sm text-gray-600 dark:text-gray-400"
			>{{ __('Salvato.') }}</p>
		@endif
	</form>
@endsection