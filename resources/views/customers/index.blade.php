@extends('layouts.app')

@section('content')

@php
$filter = request('filter');
$company = isset($filter['company']) ? Helper::getElementName('company', $filter['company']) : '';
@endphp 

	<div class="clienti">

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
		
			<header class="clienti flex items-center justify-between">
				<div class="basis-2/4">
					<h1 class="mb-5">Clienti</h1>
				</div>
		
				<a class="btn-primary basis-auto" href="{{ route('customers.create') }}">Aggiungi cliente</a>
			</header>

			<div class="filtri my-10 flex items-center justify-between">
				<div class="search">
					{{-- Cerca cliente --}}
					<form action="{{ route('customers.index') }}" method="GET">
						@csrf
						<label for="search" class="hidden">Cerca cliente</label>
						<input type="text" class="form-control" name="search" placeholder="Cerca cliente">

					</form>
				</div>

				<div>
					<b>Filtra per:</b>
					<form action="{{ route('customers.filter') }}" method="post">
						@csrf
						@method('PATCH')

						<div class="flex items-end">

							<label for="company" class="col-span-3 font-medium leading-6 text-secondary mr-5">
								<span class="block">Azienda</span>
								<div class="input-text relative">
									<input class="fake company" subject="company" role="combobox" type="text" name="" list="" data-list-id="company" value="{{ $company }}" placeholder="Cerca azienda">
									<input type="hidden" name="company" value="">
									<datalist id="company">
										@foreach ($companies as $company)
											<option value="{{ $company->id }}">{{ $company->name }}</option>
										@endforeach
									</datalist>
									<div id="datalist-company" class="safari-only safari-datalist">
										@foreach ($companies as $company)
											<div class="option" value="{{ $company->id }}"></div>
										@endforeach
									</div>
								</div>
							</label>
								
							<button type="submit" class="btn-primary">Filtra</button>
						</div>

					</form>
				</div>

				{{-- To do: implementare l'ordinamento --}}
				{{-- <div class="ordine">
					<button class="inline-flex items-center justify-between rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" id="bf_orderby" type="button" aria-haspopup="menu" aria-expanded="false" data-bf-state="">
						<span>Ordina per</span>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-5"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd"></path></svg>
					</button>

					<div class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="bf_orderby" tabindex="-1">
						<div class="py-1" role="none">
							<a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="bf_orderby-0">Nome</a>
							<a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="bf_orderby-1">Data iscrizione</a>
						</div>
					</div>

					<ul class="dropdown-ordina bg-secondary">
						<li id="sort-name hover:bg-accent-25">Nome</li>
						<li id="sort-date hover:bg-accent-25">Data iscrizione</li>
					</ul>
				</div> --}}
			</div>
		</div>

		<div class="wrap">
			@foreach ($customers as $i => $customer)
			
			@php
				$bg = $i % 2 == 0 ? 'bg-gray-100 ' : '';
			@endphp
	
				<div class="{{ $bg }}lg:flex lg:items-center lg:justify-between border-b border-gray-300 p-5">
					<div class="min-w-0 flex-1">
						<h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">{{ $customer->name }}</h2>
						<div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
							<div class="mt-2 flex items-center text-sm text-gray-500">
								<svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 -3.5 32 32" fill="currentColor" aria-hidden="true">
									<g stroke-width="0"></g>
									<g stroke-linecap="round" stroke-linejoin="round"></g>
									<g>
										<g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd" sketch:type="MSPage"> 
											<g id="Icon-Set-Filled" sketch:type="MSLayerGroup" transform="translate(-414.000000, -261.000000)"> 
												<path d="M430,275.916 L426.684,273.167 L415.115,285.01 L444.591,285.01 L433.235,273.147 L430,275.916 L430,275.916 Z M434.89,271.89 L445.892,283.329 C445.955,283.107 446,282.877 446,282.634 L446,262.862 L434.89,271.89 L434.89,271.89 Z M414,262.816 L414,282.634 C414,282.877 414.045,283.107 414.108,283.329 L425.147,271.927 L414,262.816 L414,262.816 Z M445,261 L415,261 L430,273.019 L445,261 L445,261 Z" id="mail" sketch:type="MSShapeGroup"> </path> 
											</g> 
										</g> 
									</g>
								</svg>
								{{ $customer->email }}
							</div>

							@if ($customer->company)
							@php($company = $companies->find($customer->company))
								<div class="mt-2 flex items-center text-sm text-gray-500">
									<svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" fill="currentColor" aria-hidden="true" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 472.615 472.615" xml:space="preserve" width="64px" height="64px"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M328.776,104.453V39.385H143.839v65.068h-41.097v164.386h106.595l-3.853-30.821h61.645l-3.853,30.821h106.595V104.453 H328.776z M308.227,104.453H164.388V59.934h143.839V104.453z"></path> </g> </g> <g> <g> <polygon points="260.709,289.388 256.857,320.213 215.759,320.213 211.906,289.388 102.743,289.388 102.743,433.229 369.873,433.229 369.873,289.388 "></polygon> </g> </g> <g> <g> <rect x="410.969" y="104.457" width="61.647" height="328.773"></rect> </g> </g> <g> <g> <rect y="104.457" width="61.647" height="328.773"></rect> </g> </g> </g></svg>
									{{ $company->name }}
								</div>
							@endif
						</div>
					</div>

					<div class="mt-5 flex lg:ml-4 lg:mt-0">
						<a href="{{ route('customers.edit', $customer) }}" class="hidden sm:block">
							<button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
							<svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z" />
							</svg>
							Modifica
							</button>
						</a>

						<a href="{{ route('customers.show', $customer) }}" class="hidden sm:block sm:ml-3">
							<button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
							<svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path d="M12.232 4.232a2.5 2.5 0 013.536 3.536l-1.225 1.224a.75.75 0 001.061 1.06l1.224-1.224a4 4 0 00-5.656-5.656l-3 3a4 4 0 00.225 5.865.75.75 0 00.977-1.138 2.5 2.5 0 01-.142-3.667l3-3z" />
								<path d="M11.603 7.963a.75.75 0 00-.977 1.138 2.5 2.5 0 01.142 3.667l-3 3a2.5 2.5 0 01-3.536-3.536l1.225-1.224a.75.75 0 00-1.061-1.06l-1.224 1.224a4 4 0 105.656 5.656l3-3a4 4 0 00-.225-5.865z" />
							</svg>
							Dettagli
							</button>
						</a>

						<a href="#" class="sm:ml-3">
							<button type="button" class="btn-primary inline-flex px-3 py-2">
							<svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
							</svg>
							Elimina
							</button>
						</a>

						<form action="{{ route('customers.destroy', $customer) }}" method="POST" class="hidden">
							@csrf
							@method('DELETE')
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
								<a href="{{ route('customers.show', $customer) }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="mobile-menu-item-0">Visualizza dettagli</a>
								<a href="{{ route('customers.edit', $customer) }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="mobile-menu-item-0">Modifica</a>
								<a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="mobile-menu-item-1">Elimina</a>
							</div>
						</div>
					</div>
				</div>
				
			@endforeach
		</div>

		<footer class="mt-10">
			<div class="pagination py-10">
				@if ($customers->count() > 0)
					{{ $customers->links() }}
				@endif
			</div>
		</footer>
	</div>
@endsection