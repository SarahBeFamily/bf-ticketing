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
		<span>Modifica</span>
		<h1 class="my-5">{{ $customer->name }}</h1>
		
		<form action="{{ route('customers.update', $customer->id) }}" method="POST">
			@csrf

			<div class="mb-2">
				<label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nome*</label>
				<input type="text" name="name" id="name" value="{{ $customer->name }}" required class="form-input" required>
			</div>

			<div class="mb-2">
				<label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
				<input type="email" name="email" id="email" value="{{ $customer->email }}" required>
			</div>

			<div class="mb-2">
				<label for="password" class="block text-sm font-medium leading-6 text-gray-900">Nuova Password</label>
				<input type="text" name="password" id="password" value="">
			</div>
			
			<div class="mb-2">
				<label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Telefono</label>
				<input type="text" name="phone" id="phone" value="{{ $customer->phone }}">
			</div>

			<div class="mb-2">
				<label for="address" class="block text-sm font-medium leading-6 text-gray-900">Indirizzo</label>
				<input type="text" name="address" id="address" value="{{ $customer->address }}">
			</div>

			<div>
				<label for="company" class="block text-sm font-medium leading-6 text-gray-900">Assegna Azienda</label>
				<select name="company" id="company" class="appearance-none row-start-1 col-start-1 bg-slate-50 dark:bg-slate-800">
					<option value="">Scegli</option>
					@foreach ($companies as $company)
						@php($workers = $company->workers ? json_decode($company->workers) : [])
						<option value="{{ $company->id }}" @selected(in_array($customer->id, $workers))>{{ $company->name }}</option>
					@endforeach
				</select>
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
	</div>
@endsection