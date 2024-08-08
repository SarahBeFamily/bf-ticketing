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
	@elseif (session('warning'))
		<div class="alert alert-warning">
			{{ session('warning') }}
		</div>
	@elseif ($errors->any())
		<div class="alert alert-error">
			<ul>
				@foreach ($errors->all() as $error)
					<li class="alert alert-error">{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
</div>

	<h1>Aggiungi nuovo progetto</h1>
	<p class="w-3/4 mt-4">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Saepe iste cupiditate accusamus minus sapiente hic dignissimos dolorem laudantium nesciunt. Facilis, nam repellendus in alias ipsa minus laborum aperiam eos veniam.</p>
	
	<form action="{{ route('projects.store') }}" method="POST" class="">
		@csrf
		
		<div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
			<div class="form-group sm:col-span-full">
				<label for="name" class="block text-sm font-medium leading-6 text-gray-900">Titolo (obbligatorio)</label>
				<input type="text" name="name" id="name" class="form-control block w-full" value="{{ old('name') }}" required>
			</div>
			
			<div class="form-group sm:col-span-full">
				<label for="description" class="block text-sm font-medium leading-6 text-gray-900">Descrizione</label>
				<textarea name="description" id="description" class="form-control block w-full">{{ old('description') }}</textarea>
			</div>

			<div class="form-group sm:col-span-1">
				<label for="started_at" class="block text-sm font-medium leading-6 text-gray-900">Data di inizio</label>
				<input type="date" name="started_at" id="started_at" class="form-control" value="{{ old('started_at') }}">
			</div>

			<div class="form-group sm:col-span-1">
				<label for="deadline" class="block text-sm font-medium leading-6 text-gray-900">Scadenza / Consegna</label>
				<input type="date" name="deadline" id="deadline" class="form-control" value="{{ old('deadline') }}">
			</div>
				
			
			<div class="form-group sm:col-span-1">
				<label for="division" class="block text-sm font-medium leading-6 text-gray-900">Reparto</label>
				<select name="division" id="division" class="block w-full">
					<option value="">Scegli</option>
					@if (is_array($settings->get('division')))
						@foreach ($settings->get('division') as $division)
							<option value="{{ $division }}">{{ $division }}</option>
						@endforeach
					@else
						<option value="Grafica">Grafica</option>
						<option value="Web">Web</option>
						<option value="Social">Social</option>
					@endif
				</select>
			</div>

			<div class="form-group sm:col-span-1">
				<label for="status" class="block text-sm font-medium leading-6 text-gray-900">Stato</label>
				<select name="status" id="status" class="block w-full">
					<option value="">Scegli</option>
					@if (is_array($settings->get('project_status')))
						@foreach ($settings->get('project_status') as $status)
							<option value="{{ $status }}">{{ $status }}</option>
						@endforeach
					@else
						<option value="In corso">In corso</option>
						<option value="Completato">Completato</option>
						<option value="Annullato">Annullato</option>
					@endif
				</select>
			</div>
				
			<div class="form-group sm:col-span-3">
				<label for="team" class="block text-sm font-medium leading-6 text-gray-900">Referenti</label>
				<select name="assigned_to[]" id="team" class="block w-full" multiple>
					@foreach ($team as $member)
						<option value="{{ $member->id }}">{{ $member->name }}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group sm:col-span-3">
				<label for="customer" class="block text-sm font-medium leading-6 text-gray-900">Assegna ad Azienda</label>
				<select name="company_id" id="customer" class="block w-full">
					@foreach ($companies as $company)
						<option value="{{ $company->id }}">{{ $company->name }}</option>
					@endforeach
				</select>
			</div>
		</div>

		<button type="submit" class="button btn-primary my-6">Crea</button>
	</form>
@endsection