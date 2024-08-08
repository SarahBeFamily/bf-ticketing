@extends('layouts.app')

@section('content')

@php
	$workers = $company->workers ? json_decode($company->workers) : [];
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

	<div class="aziende">
		<span>Modifica azienda</span>
		<h1 class="mb-5">{{ $company->name }}</h1>
		
		<form action="{{ route('companies.update', $company->id) }}" method="POST" enctype="multipart/form-data">
			@csrf
			@method('PATCH')

			<div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
				<div class="sm:col-span-3">
					<label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nome*</label>
					<input type="text" name="name" id="name" class="block w-full" required value="{{ $company->name }}">
				</div>

				<div class="sm:col-span-3">
					@if ($company->logo)
						<img src="{{ $company->logo }}" alt="{{ $company->name }}" class="w-20 h-20">
						<a href="{{ route('companies.delete_logo', $company->id) }}">Elimina logo</a>
					@else
						<label for="logo" class="block text-sm font-medium leading-6 text-gray-900">Logo Azienda</label>
						<input type="file" name="logo" id="logo">
					@endif
				</div>
	
				<div class="sm:col-span-full">
					<label for="workers" class="block text-sm font-medium leading-6 text-gray-900">Assegna Utenti</label>
					<select name="workers[]" id="workers" class="block w-full" multiple>
						@foreach ($users as $customer)
							@if ($customer->company == null || $customer->company == $company->id)
								<option value="{{ $customer->id }}" @selected(in_array($customer->id, $workers))>{{ $customer->name }}</option>
							@endif
						@endforeach
					</select>
				</div>

			</div>

			<button type="submit" class="btn btn-primary text-right my-3">Modifica</button>
		</form>
	</div>
@endsection