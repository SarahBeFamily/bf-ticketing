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

	<div class="aziende">
		<h1 class="mb-5">Aggiungi azienda</h1>
		
		<form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
			@csrf

			<div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
				<div class="sm:col-span-3">
					<label for="name" class="block leading-6 text-gray-900">Nome*</label>
					<input type="text" name="name" id="name" class="block w-full" required value="{{ old('name') }}">
				</div>

				<div class="sm:col-span-3">
					<label for="logo" class="block leading-6 text-gray-900">Logo Azienda</label>
					<input type="file" name="logo" id="logo">
				</div>
	
				<div class="sm:col-span-full">
					<label for="workers" class="block leading-6 text-gray-900">Assegna Utenti (che non sono già assegnati ad altre aziende)</label>
					<select name="workers[]" id="workers" class="block w-full" multiple>
						@foreach ($users as $customer)
							@if (!$customer->company)
								<option value="{{ $customer->id }}">{{ $customer->name }}</option>
							@endif
						@endforeach
					</select>
				</div>

			</div>

			<button type="submit" class="btn btn-primary text-right my-3">Aggiungi</button>
		</form>
	</div>
@endsection