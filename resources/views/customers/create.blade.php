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

	<div class="clienti">
		<h1>Aggiungi cliente</h1>
		
		<form action="{{ route('customers.store') }}" method="POST">
			@csrf

			<div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
				<div class="sm:col-span-3">
					<label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nome*</label>
					<input type="text" name="name" id="name" class="block w-full" required>
				</div>
	
				<div class="sm:col-span-3">
					<label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email*</label>
					<input type="email" name="email" id="email" class="block w-full" required>
				</div>
	
				<div class="sm:col-span-3">
					<label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password*</label>
					<input type="text" name="password" id="password" class="block w-full" required>
				</div>
				
				<div class="sm:col-span-3">
					<label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Telefono</label>
					<input type="text" name="phone" class="block w-full" id="phone">
				</div>
	
				<div class="sm:col-span-full">
					<label for="address" class="block text-sm font-medium leading-6 text-gray-900">Indirizzo</label>
					<input type="text" name="address" class="block w-full" id="address">
				</div>
	
				<div class="sm:col-span-3">
					<label for="company" class="block text-sm font-medium leading-6 text-gray-900">Azienda</label>
					<input type="text" name="company" class="block w-full" id="company">
				</div>

				<div class="sm:col-span-3">
					<label for="avatar" class="block text-sm font-medium leading-6 text-gray-900">Logo Azienda</label>
					<input type="file" name="avatar" id="avatar">
				</div>
	
				<div class="sm:col-span-full">
					<label for="progetti" class="block text-sm font-medium leading-6 text-gray-900">Assegna Progetti</label>
					<select name="progetti[]" id="progetti" class="block w-full" multiple>
						@foreach ($projects as $project)
							<option value="{{ $project->id }}">{{ $project->name }}</option>
						@endforeach
					</select>
				</div>

			</div>

			<button type="submit" class="btn btn-primary text-right my-3">Aggiungi</button>
		</form>
	</div>
@endsection