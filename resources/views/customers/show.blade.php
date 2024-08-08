@extends('layouts.app')

@section('content')
	<div class="clienti">
			
		<div class="card">
			<h1 class="mb-5">{{ $customer->name }}</h1>
			
			<table class="table-auto">
				<tr>
					<td>Nome:</td>
					<td>{{ $customer->name }}</td>
				</tr>
				<tr>
					<td>Telefono:</td>
					<td>{{ $customer->phone }}</td>
				</tr>
				<tr>
					<td>Email:</td>
					<td>{{ $customer->email }}</td>
				</tr>
				<tr>
					<td>Azienda:</td>
					<td>
						@if ($customer->company)
							<a href="{{ route('companies.show', $customer->company->id) }}">{{ $customer->company->name }}</a>
						@else
							Non assegnata - <a href="{{ route('customers.edit', $customer) }}" class="button btn-primary mt-2">Assegna ora</a>
						@endif
					</td>
				</tr>
			</table>
			
			<a href="{{ route('customers.edit', $customer) }}" class="button btn-primary my-6">Modifica</a>
		</div>

	</div>
@endsection