@extends('layouts.app')

@section('content')
	<div class="clienti">

		<h1>Clienti</h1>

		<div class="actions">
			<a href="{{ route('customers.create') }}">Aggiungi cliente</a>
		</div>

		<div class="wrap">
			@foreach ($customers as $customer)
			
				<div class="card">
					<h2>{{ $customer->name }}</h2>
					<p>{{ $customer->email }}</p>
					
					<div class="actions">
						<form action="{{ route('customers.destroy', $customer) }}" method="POST">
							@csrf
							@method('DELETE')
							<button type="submit">Elimina</button>
						</form>
						<a href="{{ route('customers.show', $customer) }}">Visualizza dettagli</a>
						<a href="{{ route('customers.edit', $customer) }}">Modifica</a>
					</div>
				</div>
				
			@endforeach
		</div>
	</div>
@endsection