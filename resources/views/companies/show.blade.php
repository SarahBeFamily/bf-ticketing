@extends('layouts.app')

@section('content')
	<div class="clienti">
			
		<div class="card">
			<h1 class="mb-5">{{ $company->name }}</h1>
			
			<table class="dettagli table-auto">
				<thead>
					<tr class="bg-secondary text-white">
						<th class="text-left py-2 px-4" scope="col">Nome</th>
						<th class="text-left py-2 px-4" scope="col">Logo</th>
						<th class="text-left py-2 px-4" scope="col">Utenti assegnati</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td>{{ $company->name }}</td>
						<td><img src="{{ $company->logo }}" alt="{{ $company->name }}" class="w-50 h-auto"></td>
						<td>
							@php
								$workers = $company->workers ? json_decode($company->workers) : [];
							@endphp
							@if (count($workers) == 0)
								Nessun utente assegnato<br>
								<a href="{{ route('companies.edit', $company) }}" class="button">Assegna ora</a>
							@else
								@foreach ($workers as $worker)
									@php
										$worker = App\Models\User::find($worker);
										$worker_company = App\Models\Company::find($worker->company_id);
										$company_name = $worker_company ? '('.$worker_company->name.')' : '';
									@endphp
									<a href="{{ route('profile.edit', $worker->id) }}">{{ $worker->name }} {{ $company_name }}</a>
								@endforeach
							@endif
						</td>
					</tr>
				</tbody>
			</table>
			
			<a href="{{ route('companies.edit', $company) }}" class="button btn-primary my-6">Modifica</a>
		</div>

	</div>
@endsection