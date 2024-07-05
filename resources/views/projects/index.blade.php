@extends('layouts.app')

@section('content')

@php
	$filter = request('filter');
	$division = $filter['division'] ?? '';
	$status = $filter['status'] ?? '';
@endphp 

	<div class="wrapper flex flex-col justify-between">
		<div class="header-wrap">
			<div class="messages">
				@if (session('success'))
					<div class="alert alert-success">
						{{ session('success') }}
					</div>
				@elseif (session('error'))
					<div class="alert alert-error">
						{{ session('error') }}
					</div>
				@endif
			</div>
		
			<header class="progetti flex items-center justify-between">
				<div class="basis-2/4">
					<h1 class="mb-5">Progetti</h1>
					<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Saepe iste cupiditate accusamus minus sapiente hic dignissimos dolorem laudantium nesciunt. Facilis, nam repellendus in alias ipsa minus laborum aperiam eos veniam.</p>
				</div>
		
				<a class="btn-primary basis-auto" href="{{ route('projects.create') }}">Crea un nuovo progetto</a>
			</header>
			
			<div class="filtri my-10 flex items-center justify-between">
				<div>
					<b>Filtra per:</b>
					<form action="{{ route('projects.filter', $projects) }}" method="post">
						@csrf
						@method('PATCH')
			
						<div class="flex items-end">
							<label for="division" class="col-span-3 font-medium leading-6 text-secondary">
								<span class="block">Reparto</span>
								<select name="division" id="division">
									<option value="">Tutti</option>
									<option value="brand" @selected($division == 'brand')>Grafica</option>
									<option value="web" @selected($division == 'web')>Web</option>
									<option value="social" @selected($division == 'social')>Social</option>
								</select>
							</label>
						
							<label for="status" class="col-span-3 font-medium leading-6 text-secondary mx-5">
								<span class="block">Stato</span>
								<select name="status" id="status">
									<option value="">Tutti</option>
									<option value="development" @selected($status == 'development')>In corso</option>
									<option value="completed" @selected($status == 'completed')>Completato</option>
									<option value="pending" @selected($status == 'pending')>In attesa</option>
								</select>
							</label>
			
							<button type="submit" class="btn-primary">Filtra</button>
						</div>
			
					</form>
					<div class="filtri-attivi">
						@if (request('division'))
							<span>Reparto: {{ ucfirst(request('division')) }}</span>
						@endif
			
						@if (request('status'))
							<span>Stato: {{ ucfirst(request('status')) }}</span>
						@endif
					</div>
				</div>

				<div class="ordine">
					<button class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" id="bf_orderby" type="button" aria-haspopup="menu" aria-expanded="false" data-bf-state="">
						<span>Ordina per</span>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="of si axx"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd"></path></svg>
					</button>
				</div>
			</div>
		
		</div>

		<div class="progetti">
			
			@foreach ($projects as $i => $project)

				@php
					$bg = $i % 2 == 0 ? 'bg-gray-100 ' : '';
					$customer = App\Models\User::find($project->user_id);
					$user = App\Models\User::find($project->assigned_to);
					$referenti = [];
					if ($user) {
						foreach ($user as $team) {
							$referenti[] = $team->name;
						}
					}
				@endphp
				<div class="{{ $bg }}lg:flex lg:items-center lg:justify-between border-b border-gray-300 p-5">
					<div class="min-w-0 flex-1">
						<h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">{{ $project->name }}</h2>
						<div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
							<div class="mt-2 flex items-center text-sm text-gray-500">
								<svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
									<path fill-rule="evenodd" d="M6 3.75A2.75 2.75 0 018.75 1h2.5A2.75 2.75 0 0114 3.75v.443c.572.055 1.14.122 1.706.2C17.053 4.582 18 5.75 18 7.07v3.469c0 1.126-.694 2.191-1.83 2.54-1.952.599-4.024.921-6.17.921s-4.219-.322-6.17-.921C2.694 12.73 2 11.665 2 10.539V7.07c0-1.321.947-2.489 2.294-2.676A41.047 41.047 0 016 4.193V3.75zm6.5 0v.325a41.622 41.622 0 00-5 0V3.75c0-.69.56-1.25 1.25-1.25h2.5c.69 0 1.25.56 1.25 1.25zM10 10a1 1 0 00-1 1v.01a1 1 0 001 1h.01a1 1 0 001-1V11a1 1 0 00-1-1H10z" clip-rule="evenodd" />
									<path d="M3 15.055v-.684c.126.053.255.1.39.142 2.092.642 4.313.987 6.61.987 2.297 0 4.518-.345 6.61-.987.135-.041.264-.089.39-.142v.684c0 1.347-.985 2.53-2.363 2.686a41.454 41.454 0 01-9.274 0C3.985 17.585 3 16.402 3 15.055z" />
								</svg>
								{{ $project->division }}
							</div>

							<div class="mt-2 flex items-center text-sm text-gray-500">
								<svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
									<g stroke-width="0"></g>
									<g stroke-linecap="round" stroke-linejoin="round"></g>
									<g> 
										<path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z"></path> 
										<path d="M12.0002 14.5C6.99016 14.5 2.91016 17.86 2.91016 22C2.91016 22.28 3.13016 22.5 3.41016 22.5H20.5902C20.8702 22.5 21.0902 22.28 21.0902 22C21.0902 17.86 17.0102 14.5 12.0002 14.5Z"></path> 
									</g>
								</svg>
								{{ $customer->name }}
							</div>

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
								{{ implode(', ', $referenti) }}
							</div>

							@if ($project->deadline)
								<div class="mt-2 flex items-center text-sm text-gray-500">
									<svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
										<path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" />
									</svg>
									Scadenza {{ $project->deadline }}
								</div>
							@endif
						</div>
					</div>

					<div class="mt-5 flex lg:ml-4 lg:mt-0">
						<a href="{{ route('projects.edit', $project) }}" class="hidden sm:block">
							<button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
							<svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z" />
							</svg>
							Modifica
							</button>
						</a>
					
						<a href="{{ route('projects.show', $project) }}" class="ml-3 hidden sm:block">
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
							Tickets
							</button>
						</a>
					
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
								<a href="{{ route('projects.edit', $project) }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="mobile-menu-item-0">Modifica</a>
								<a href="{{ route('projects.show', $project) }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="mobile-menu-item-1">Dettagli</a>
							</div>
						</div>
					</div>
				</div>
				
			@endforeach
		</div>

		<footer class="mt-10">
			<div class="pagination py-10">
				{{ $projects->links() }}
			</div>
		</footer>
	</div>
@endsection