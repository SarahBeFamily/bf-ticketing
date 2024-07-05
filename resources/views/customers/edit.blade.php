@extends('layouts.app')

@section('content')

	@php
		$projects = [];
		$allProjects = App\Models\Project::all();
		foreach ($allProjects as $key => $value) {
			if ($value->user_id === '' || $value->user_id === null || $value->user_id === $customer->id) {
				$projects[] = $value;
			}
		}
	@endphp

	<div class="clienti">
		<h1></h1>
		
		<form action="{{ route('customers.update', $customer->id) }}" method="POST">
			@csrf

			<div>
				@if ($errors->any())
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				@endif
			</div>

			<div>
				<label for="name">Nome</label>
				<input type="text" name="name" id="name" value="{{ $customer->name }}" required class="form-input">
			</div>

			<div>
				<label for="email">Email</label>
				<input type="email" name="email" id="email" value="{{ $customer->email }}" required>
			</div>

			<div>
				<label for="password">Nuova Password</label>
				<input type="text" name="password" id="password" value="">
			</div>
			
			<div>
				<label for="phone">Telefono</label>
				<input type="text" name="phone" id="phone" value="{{ $customer->phone }}">
			</div>

			<div>
				<label for="address">Indirizzo</label>
				<input type="text" name="address" id="address" value="{{ $customer->address }}">
			</div>

			<div>
				<label for="progetti">Assegna Progetti</label>
				<select name="progetti[]" id="progetti" multiple class="appearance-none row-start-1 col-start-1 bg-slate-50 dark:bg-slate-800">
					@foreach ($projects as $project)
						<option value="{{ $project->id }}" {{ $customer->id === $project->user_id ? 'selected' : '' }}>{{ $project->name }}</option>
					@endforeach
				</select>
			</div>

			<button type="submit">Modifica</button>
		</form>
	</div>
@endsection