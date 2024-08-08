@extends('layouts.app')

@section('content')
	<div class="aziende">

		<div class="header-wrap mb-10">
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
		
			<header class="progetti flex items-center justify-between">
				<div class="basis-2/4">
					<h1 class="mb-5">Aziende</h1>
				</div>
		
				<a class="btn-primary basis-auto" href="{{ route('companies.create') }}">Aggiungi azienda</a>
			</header>

			<div class="filtri my-10 flex items-center justify-between">
				<div class="search">
					{{-- Cerca cliente --}}
					<form action="" method="GET">
						@csrf
						<label for="search" class="hidden">Cerca azienda</label>
						<input type="text" class="form-control" name="search" placeholder="Cerca azienda">
					</form>
				</div>

				{{-- To do: implementare l'ordinamento --}}
				<div class="ordine">
					<button class="inline-flex items-center justify-between rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" id="bf_orderby" type="button" aria-haspopup="menu" aria-expanded="false" data-bf-state="">
						<span>Ordina per</span>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-5"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd"></path></svg>
					</button>
				</div>
			</div>
		</div>

		<div class="wrap">
			@foreach ($companies as $i => $company)
			
			@php
				$bg = $i % 2 == 0 ? 'bg-gray-100 ' : '';
				$workers = $company->workers && is_array($company->workers) ? count($company->workers) : json_decode($company->workers);
			@endphp
	
				<div class="{{ $bg }}lg:flex lg:items-center lg:justify-between border-b border-gray-300 p-5">
					<div class="logo min-w-0 mr-5">
						<img src="{{ $company->logo }}" alt="{{ $company->name }}" class="h-8 w-auto object-fit-contain">
					</div>
					
					<div class="min-w-0 flex-1">
						<h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">{{ $company->name }}</h2>
						<div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
							<div class="mt-2 flex items-center text-sm text-gray-500">
								<svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
									<g stroke-width="0"></g>
									<g stroke-linecap="round" stroke-linejoin="round"></g>
									<g> 
										<g> 
											<path fill="none" d="M0 0h24v24H0z"></path> 
											<path d="M12 11a5 5 0 0 1 5 5v6H7v-6a5 5 0 0 1 5-5zm-6.712 3.006a6.983 6.983 0 0 0-.28 1.65L5 16v6H2v-4.5a3.5 3.5 0 0 1 3.119-3.48l.17-.014zm13.424 0A3.501 3.501 0 0 1 22 17.5V22h-3v-6c0-.693-.1-1.362-.288-1.994zM5.5 8a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zm13 0a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM12 2a4 4 0 1 1 0 8 4 4 0 0 1 0-8z"></path> 
										</g> 
									</g>
								</svg>
								{{ count($workers) === 1 ? count($workers).' account collegato' : ' account collegati' }}
							</div>
						</div>
					</div>

					<div class="mt-5 flex lg:ml-4 lg:mt-0">
						<a href="{{ route('companies.edit', $company) }}" class="hidden sm:block">
							<button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
							<svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z" />
							</svg>
							Modifica
							</button>
						</a>

						<a href="{{ route('companies.show', $company) }}" class="hidden sm:block sm:ml-3">
							<button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
							<svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path d="M12.232 4.232a2.5 2.5 0 013.536 3.536l-1.225 1.224a.75.75 0 001.061 1.06l1.224-1.224a4 4 0 00-5.656-5.656l-3 3a4 4 0 00.225 5.865.75.75 0 00.977-1.138 2.5 2.5 0 01-.142-3.667l3-3z" />
								<path d="M11.603 7.963a.75.75 0 00-.977 1.138 2.5 2.5 0 01.142 3.667l-3 3a2.5 2.5 0 01-3.536-3.536l1.225-1.224a.75.75 0 00-1.061-1.06l-1.224 1.224a4 4 0 105.656 5.656l3-3a4 4 0 00-.225-5.865z" />
							</svg>
							Dettagli
							</button>
						</a>

						<form action="{{ route('companies.destroy', $company) }}" method="POST" class="sm:ml-3">
							@csrf
							@method('DELETE')
							<button type="submit" class="btn-primary inline-flex px-3 py-2">
								<svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
									<path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
								</svg>
								Elimina
							</button>
						</form>

						<!-- Dropdown -->
						<div class="relative ml-3 sm:hidden">
							<button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:ring-gray-400" id="mobile-menu-button" aria-expanded="false" aria-haspopup="true">
								More
								<svg class="-mr-1 ml-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
									<path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
								</svg>
							</button>

							<!--
							Dropdown menu, show/hide based on menu state.

							Entering: "transition ease-out duration-200"
								From: "transform opacity-0 scale-95"
								To: "transform opacity-100 scale-100"
							Leaving: "transition ease-in duration-75"
								From: "transform opacity-100 scale-100"
								To: "transform opacity-0 scale-95"
							-->
							<div class="absolute right-0 z-10 -mr-1 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="mobile-menu-button" tabindex="-1">
								<!-- Active: "bg-gray-100", Not Active: "" -->
								<a href="{{ route('companies.show', $company) }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="mobile-menu-item-0">Visualizza dettagli</a>
								<a href="{{ route('companies.edit', $company) }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="mobile-menu-item-0">Modifica</a>
								<form action="{{ route('companies.destroy', $company) }}" method="POST" role="menuitem" tabindex="-1" id="mobile-menu-item-0">
									@csrf
									@method('DELETE')
									<button type="submit" class="btn-primary inline-flex px-3 py-2">
										<svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
											<path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
										</svg>
										Elimina
									</button>
								</form>
							</div>
						</div>
					</div>
				</div>
				
			@endforeach
		</div>

		<footer class="mt-10">
			<div class="pagination py-10">
				@if ($companies->count())
					{{ $companies->links() }}					
				@endif
			</div>
		</footer>
	</div>
@endsection