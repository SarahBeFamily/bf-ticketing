@extends('layouts.app')

@section('content')

	<h1>Aggiungi nuovo progetto</h1>
	<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Saepe iste cupiditate accusamus minus sapiente hic dignissimos dolorem laudantium nesciunt. Facilis, nam repellendus in alias ipsa minus laborum aperiam eos veniam.</p>
	
	<form action="{{ route('projects.store') }}" method="POST">
		@csrf
		
		<div class="form-group">
			<label for="name">Nome</label>
			<input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
		</div>
		
		<div class="form-group">
			<label for="description">Descrizione</label>
			<textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
		</div>

		<div class="form-group">
			<label for="started_at">Data di inizio</label>
			<input type="date" name="started_at" id="started_at" class="form-control" value="{{ old('started_at') }}">
			
			<label for="division">Reparto</label>
			<select name="division" id="division">
				<option value="">Scegli</option>
				<option value="Grafica">Grafica</option>
				<option value="Web">Web</option>
				<option value="Social">Social</option>
			</select>

			<label for="status">Stato</label>
			<select name="status" id="status">
				<option value="">Scegli</option>
				<option value="In corso">In corso</option>
				<option value="Completato">Completato</option>
				<option value="In attesa">In attesa</option>
			</select>

			<label for="deadline">Scadenza</label>
			<input type="date" name="deadline" id="deadline" class="form-control" value="{{ old('deadline') }}">

			<label for="team">Referenti</label>
			<select name="assigned_to[]" id="team" multiple>
				@foreach ($team as $member)
					<option value="{{ $member->id }}">{{ $member->name }}</option>
				@endforeach
			</select>
		</div>

		<div class="form-group">
			<label for="customer">Assegna a Cliente</label>
			<select name="user_id" id="customer">
				@foreach ($customers as $customer)
					<option value="{{ $customer->id }}">{{ $customer->name }}</option>
				@endforeach
			</select>
		</div>

		<button type="submit" class="button btn-primary">Crea</button>
	</form>
@endsection