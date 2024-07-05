@extends('layouts.app')

@section('content')
	<div class="clienti">
		<h1>Aggiungi cliente</h1>
		
		<form action="{{ route('customers.store') }}" method="POST">
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
				<input type="text" name="name" id="name" required>
			</div>

			<div>
				<label for="email">Email</label>
				<input type="email" name="email" id="email" required>
			</div>

			<div>
				<label for="password">Password</label>
				<input type="text" name="password" id="password" required>
			</div>
			
			<div>
				<label for="phone">Telefono</label>
				<input type="text" name="phone" id="phone">
			</div>

			<div>
				<label for="address">Indirizzo</label>
				<input type="text" name="address" id="address">
			</div>

			<button type="submit">Aggiungi</button>
		</form>
	</div>
@endsection