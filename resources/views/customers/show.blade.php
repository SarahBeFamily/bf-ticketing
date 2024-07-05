@extends('layouts.app')

@section('content')
	<div class="clienti">
			
		<div class="card">
			<h1>{{ $customer->name }}</h1>
			<p>{{ $customer->email }}</p>
			
			<a href="{{ route('customers.edit', $customer) }}">Modifica</a>
		</div>

	</div>
@endsection