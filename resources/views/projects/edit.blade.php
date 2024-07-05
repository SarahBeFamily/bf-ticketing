@extends('layouts.app')

@section('content')

<div class="messages">
	@if (session('success'))
		<div class="alert alert-success">
			{{ session('success') }}
		</div>
	@elseif (session('error'))
		<div class="alert alert-error">
			{{ session('error') }}
		</div>
	@endif
</div>

	<span>Modifica progetto</span>
	<h1>{{ $project->name }}</h1>

	<p class="sm:col-span-5">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Saepe iste cupiditate accusamus minus sapiente hic dignissimos dolorem laudantium nesciunt. Facilis, nam repellendus in alias ipsa minus laborum aperiam eos veniam.</p>
	
	<form action="{{ route('projects.update', $project->id) }}" method="POST">
		@csrf
		@method('PATCH')
		
		<div class="form-group">
			<label for="name">Nome</label>
			<input 
				type="text" 
				name="name" 
				id="name" 
				class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" 
				value="{{ $project->name }}">
		</div>
		
		<div class="form-group">
			<label for="description">Descrizione</label>
			<textarea 
				name="description" 
				id="description" 
				class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">{{ $project->description }}</textarea>
		</div>

		<div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
			<label for="start_date">Data di inizio</label>
			<input type="date" name="start_date" id="start_date" class="form-control" value="{{ $project->start_date }}">

			<label for="deadline">Scadenza</label>
			<input type="date" name="deadline" id="deadline" class="form-control" value="{{ old('deadline') }}">
		</div>

		<div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
			<label for="division">Reparto</label>
			<select name="division" id="division">
				<option value="Grafica" @selected($project->division == 'Grafica')>Grafica</option>
				<option value="Web" @selected($project->division == 'Web')>Web</option>
				<option value="Social" @selected($project->division == 'Social')>Social</option>
			</select>

			<label for="status">Stato</label>
			<select name="status" id="status">
				<option value="In corso" @selected($project->status == 'In corso')>In corso</option>
				<option value="Completato" @selected($project->status == 'Completato')>Completato</option>
				<option value="In attesa" @selected($project->status == 'In attesa')>In attesa</option>
			</select>
		</div>

		<div class="form-group">
			<label for="team">Referenti</label>
			<select name="assigned_to[]" id="team" multiple>
				@foreach ($team as $member)
					<option value="{{ $member->id }}" @selected(in_array($member->id, $project->assigned_to))>{{ $member->name }}</option>
				@endforeach
			</select>

			<label for="customer">Assegna a Cliente</label>
			<select name="user_id" id="customer" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
				@foreach ($customers as $customer)
					<option value="{{ $customer->id }}" @selected($project->user_id == $customer->id)>{{ $customer->name }}</option>
				@endforeach
			</select>
		</div>

		<button type="submit" class="button inline-flex items-center rounded-md bg-accent-100 px-3 py-2 text-sm font-medium text-white shadow-lg ring-1 ring-inset ring-gray-300 hover:bg-secondary transition-all">Salva</button>
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