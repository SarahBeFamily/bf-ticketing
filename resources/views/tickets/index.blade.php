@extends('layouts.app')

@section('content')

@php
	$filter = request('filter');
	$division = $filter['division'] ?? '';
	$status = $filter['status'] ?? '';
	$company_id = isset($filter['company_id']) ? Helper::getElementName('company', $filter['company_id']) : '';
	$project_id = isset($filter['project_id']) ? Helper::getElementName('project', $filter['project_id']) : '';
	$project_parent = isset($filter['project_id']) ? '?project_id='.$filter['project_id'] : '';
	$user_id = isset($filter['user_id']) ? Helper::getElementName('user', $filter['user_id']) : '';
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

			<header class="tickets flex items-center justify-between">
				<div class="basis-2/4">
					<h1 class="mb-5">Tickets</h1>
					<p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Saepe iste cupiditate accusamus minus sapiente hic dignissimos dolorem laudantium nesciunt. Facilis, nam repellendus in alias ipsa minus laborum aperiam eos veniam.</p>
				</div>

				<a class="btn-primary basis-auto" href="{{ route('tickets.create') }}{{ $project_parent }}">Apri un nuovo ticket</a>
			</header>

			<div class="filtri my-10 flex items-end justify-between">
				<div>
					<b>Filtra per:</b>
					<form action="{{ route('tickets.filter') }}" method="post">
						@csrf
						@method('PATCH')

						<div class="flex items-end">
							<label for="division" class="col-span-3 font-medium leading-6 text-secondary">
								<span class="block">Reparto</span>
								<select name="division" id="division">
									<option value="">Tutti</option>
									@if (is_array($settings->get('division')))
										@foreach ($settings->get('division') as $division)
											<option value="{{ $division }}" @selected($division == $division)>{{ $division }}</option>
										@endforeach
									@else
										<option value="Grafica" @selected($division == 'Grafica')>Grafica</option>
										<option value="Web" @selected($division == 'Web')>Web</option>
										<option value="Social" @selected($division == 'Social')>Social</option>
									@endif
								</select>
							</label>

							<label for="status" class="col-span-3 font-medium leading-6 text-secondary ml-5">
								<span class="block">Stato</span>
								<select name="status" id="status">
									<option value="">Tutti</option>
									<option value="Aperto" @selected($status == 'Aperto')>Aperto</option>
									<option value="Chiuso" @selected($status == 'Chiuso')>Chiuso</option>
								</select>
							</label>

							@can('edit users')
							<label for="company_id" class="col-span-3 font-medium leading-6 text-secondary ml-5">
								<span class="block">Azienda</span>
								<div class="input-text relative">
									<input class="fake company_id" subject="company_id" role="combobox" type="text" name="" list="" data-list-id="company_id" value="{{ $company_id }}" placeholder="Cerca azienda">
									<input type="hidden" name="company_id" value="">
									<datalist id="company_id">
										@foreach ($companies as $company)
											<option value="{{ $company->id }}">{{ $company->name }}</option>
										@endforeach
									</datalist>
									<div id="datalist-company_id" class="safari-only safari-datalist">
										@foreach ($companies as $company)
											<div class="option" value="{{ $company->id }}"></div>
										@endforeach
									</div>
								</div>
							</label>
							@endcan

							<label for="project_id" class="col-span-3 font-medium leading-6 text-secondary mx-5">
								<span class="block">Progetto</span>
								<div class="input-text relative">
									<input class="fake project_id" subject="project_id" role="combobox" type="text" name="" list="" data-list-id="project_id" value="{{ $project_id }}" placeholder="Cerca progetto">
									<input type="hidden" name="project_id" value="">
									<datalist id="project_id">
										@foreach ($projects as $project)
											<option value="{{ $project->id }}">{{ $project->name }}</option>
										@endforeach
									</datalist>
									<div id="datalist-project_id" class="safari-only safari-datalist">
										@foreach ($projects as $project)
											<div class="option" value="{{ $project->id }}"></div>
										@endforeach
									</div>
								</div>
							</label>

							@can('edit users')

							<label for="user_id" class="col-span-3 font-medium leading-6 text-secondary mr-5">
								<span class="block">Cliente</span>
								<div class="input-text relative">
									<input class="fake user_id" subject="user_id" role="combobox" type="text" name="" list="" data-list-id="user_id" value="{{ $user_id }}" placeholder="Cerca cliente">
									<input type="hidden" name="user_id" value="">
									<datalist id="user_id">
										@foreach ($customers as $customer)
											<option value="{{ $customer->id }}">{{ $customer->name }}</option>
										@endforeach
									</datalist>
									<div id="datalist-user_id" class="safari-only safari-datalist">
										@foreach ($customers as $customer)
											<div class="option" value="{{ $customer->id }}"></div>
										@endforeach
									</div>
								</div>
							</label>
								
							@endcan

							<input type="hidden" name="sort" value="">
							<button type="submit" class="btn-primary">Filtra</button>
						</div>

					</form>
				</div>

				<div class="ordine relative transition duration-150 ease-in-out" x-data="{ open: '' }">
					<button @click="open = ! open" class="inline-flex items-center justify-between rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" id="bf_orderby" type="button" aria-haspopup="menu" aria-expanded="false" data-bf-state="">
						<span>Ordina per</span>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="h-5"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z" clip-rule="evenodd"></path></svg>
					</button>

					<ul id="dropdown-ordina-projects" class="dropdown-ordina-projects absolute bg-secondary text-white w-full" :class="{'block': open, 'hidden': ! open}">
						<li data-orderby="deadline" class="hover:bg-accent-100 p-2 cursor-pointer">Scadenza ↑</li>
						<li data-orderby="-deadline" class="hover:bg-accent-100 p-2 cursor-pointer">Scadenza ↓</li>
						<li data-orderby="started_at" class="hover:bg-accent-100 p-2 cursor-pointer">Data di inizio ↑</li>
						<li data-orderby="-started_at" class="hover:bg-accent-100 p-2 cursor-pointer">Data di inizio ↓</li>
					</ul>
				</div>
			</div>

			<div class="filtri-attivi mb-2">
				@if ($filter)
					<span>Filtri attivi:</span>
					@foreach($filter as $f)
						<span class="bg-secondary text-white px-2 py-1 rounded-full mr-2">{{ $f }}</span>
					@endforeach
				@endif
			</div>

		</div>

		<div class="tickets">

			@foreach ($tickets as $i => $ticket)

				@php
					$bg = $i % 2 == 0 ? 'bg-gray-100 ' : '';
					$project = App\Models\Project::find($ticket->project_id);
					$company = $project ? App\Models\Company::find($project->company_id) : null;
					$user = $ticket->assigned_to;
					$customer = App\Models\User::find($ticket->user_id);
					$comments = $ticket->getComments();
					$referenti = [];
					if ($user && is_array($user)) {
						foreach ($user as $team) {
							$referenti[] = App\Models\User::find($team)->name;
						}
					} else if ($user) {
						$referenti[] = App\Models\User::find($user)->name;
					}
				@endphp
				<div class="{{ $bg }}lg:flex lg:items-center lg:justify-between border-b border-gray-300 p-5">
					<div class="min-w-0 flex-1">
						<h2 class="mb-2 text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">{{ $ticket->subject }}</h2>
						<div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">

							@can('edit users')
							<div class="mt-2 flex items-center text-sm text-gray-500">
								ID #{{ $ticket->id }}
							</div>
							@endcan

							<div class="mt-2 flex items-center text-sm text-gray-500">
								<svg class="details" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
									<path fill-rule="evenodd" d="M6 3.75A2.75 2.75 0 018.75 1h2.5A2.75 2.75 0 0114 3.75v.443c.572.055 1.14.122 1.706.2C17.053 4.582 18 5.75 18 7.07v3.469c0 1.126-.694 2.191-1.83 2.54-1.952.599-4.024.921-6.17.921s-4.219-.322-6.17-.921C2.694 12.73 2 11.665 2 10.539V7.07c0-1.321.947-2.489 2.294-2.676A41.047 41.047 0 016 4.193V3.75zm6.5 0v.325a41.622 41.622 0 00-5 0V3.75c0-.69.56-1.25 1.25-1.25h2.5c.69 0 1.25.56 1.25 1.25zM10 10a1 1 0 00-1 1v.01a1 1 0 001 1h.01a1 1 0 001-1V11a1 1 0 00-1-1H10z" clip-rule="evenodd" />
									<path d="M3 15.055v-.684c.126.053.255.1.39.142 2.092.642 4.313.987 6.61.987 2.297 0 4.518-.345 6.61-.987.135-.041.264-.089.39-.142v.684c0 1.347-.985 2.53-2.363 2.686a41.454 41.454 0 01-9.274 0C3.985 17.585 3 16.402 3 15.055z" />
								</svg>
								{{ $project ? $project->division.' -' : '' }} {{ $ticket->type }}
							</div>

							@if ($project)
							<div class="mt-2 flex items-center text-sm text-gray-500">
								<svg class="details" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
									<path fill-rule="evenodd" d="M6 3.75A2.75 2.75 0 018.75 1h2.5A2.75 2.75 0 0114 3.75v.443c.572.055 1.14.122 1.706.2C17.053 4.582 18 5.75 18 7.07v3.469c0 1.126-.694 2.191-1.83 2.54-1.952.599-4.024.921-6.17.921s-4.219-.322-6.17-.921C2.694 12.73 2 11.665 2 10.539V7.07c0-1.321.947-2.489 2.294-2.676A41.047 41.047 0 016 4.193V3.75zm6.5 0v.325a41.622 41.622 0 00-5 0V3.75c0-.69.56-1.25 1.25-1.25h2.5c.69 0 1.25.56 1.25 1.25zM10 10a1 1 0 00-1 1v.01a1 1 0 001 1h.01a1 1 0 001-1V11a1 1 0 00-1-1H10z" clip-rule="evenodd" />
									<path d="M3 15.055v-.684c.126.053.255.1.39.142 2.092.642 4.313.987 6.61.987 2.297 0 4.518-.345 6.61-.987.135-.041.264-.089.39-.142v.684c0 1.347-.985 2.53-2.363 2.686a41.454 41.454 0 01-9.274 0C3.985 17.585 3 16.402 3 15.055z" />
								</svg>
								{{ $project->name }} {{ $company ? ' | '.$company->name : '' }}
							</div>
							@endif

							<div class="mt-2 flex items-center text-sm text-gray-500">
								<svg class="details" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
									<g stroke-width="0"></g>
									<g stroke-linecap="round" stroke-linejoin="round"></g>
									<g>
										<path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z"></path>
										<path d="M12.0002 14.5C6.99016 14.5 2.91016 17.86 2.91016 22C2.91016 22.28 3.13016 22.5 3.41016 22.5H20.5902C20.8702 22.5 21.0902 22.28 21.0902 22C21.0902 17.86 17.0102 14.5 12.0002 14.5Z"></path>
									</g>
								</svg>
								{{ $customer->name }} | il {{ $ticket->created_at->format('d/m/Y') }} alle ore: {{ $ticket->created_at->format('H:i') }}
							</div>

							@if(!empty($referenti))
							<div class="mt-2 flex items-center text-sm text-gray-500">
								<svg class="details" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
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
							@endif

							<div class="mt-2 flex items-center text-sm text-gray-500">
								<svg class="details" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" enable-background="new 0 0 52 52" xml:space="preserve">
									<g stroke-width="0"></g>
									<g stroke-linecap="round" stroke-linejoin="round"></g>
									<g> 
										<path d="M17.6,11.4c-8.9,0-16,6.5-16,14.7c0,2.6,0.7,5,2,7.2c0.1,0.3,0.2,0.6,0.1,0.9l-1.5,4.6 c-0.3,0.9,0.5,1.6,1.4,1.3l4.7-1.6c0.3-0.1,0.7-0.1,0.9,0.1c2.5,1.4,5.3,2.2,8.4,2.2c8.9,0,16-6.5,16-14.7 C33.6,17.9,26.4,11.4,17.6,11.4z M25.3,22.8l-8.1,8c-0.3,0.3-0.7,0.5-1.1,0.5c-0.4,0-0.8-0.1-1.1-0.5L11,26.9 c-0.3-0.3-0.3-0.8,0-1.1l1.1-1.1c0.3-0.3,0.8-0.3,1.1,0l2.8,2.8l7-6.9c0.3-0.3,0.8-0.3,1.1,0l1.1,1.1C25.6,22,25.6,22.5,25.3,22.8z"></path> 
										<path d="M34.3,11.4h-4.2c4.7,3.4,8.2,8.8,8.2,14.7c0,5.9-3.7,11.5-8.2,14.7h4.2c8.9,0,16-6.5,16-14.7 C50.2,17.9,43.1,11.4,34.3,11.4z"></path> 
									</g>
								</svg>
								{{ $comments->count() }} {{ $comments->count() == 1 ? 'risposta' : 'risposte' }}
							</div>

							<div class="status flex items-end text-sm">
								@if ($ticket->status == 'Aperto')
									<i class="icon-ticket-open h-5"></i>
								@else
									<i class="icon-ticket-closed h-5"></i>
								@endif

								<span class="status-{{ $ticket->status }} inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-{{ $ticket->status == 'Aperto' ? 'green' : 'red' }}-100 text-{{ $ticket->status == 'Aperto' ? 'green' : 'red' }}-800">
									{{ $ticket->status }}
								</span>
							</div>
						</div>
					</div>

					<div class="mt-5 flex lg:ml-4 lg:mt-0">
						<a href="{{ route('tickets.show', $ticket) }}" class="hidden sm:block">
							<button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
			
							@if ($ticket->status == 'Aperto')
								<svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
									<path d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z" />
								</svg>
								Leggi / Rispondi
							@else
								Visualizza
							@endif
							
							</button>
						</a>

						@can('edit users')
							@if ($ticket->status == 'Aperto')
								<a href="{{ route('tickets.close', $ticket) }}" class="sm:ml-3">
									<button type="button" class="btn-primary inline-flex px-3 py-2">
									<svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
										<path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
									</svg>
									Chiudi
									</button>
								</a>
							@endif
						@endcan

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
								<a href="{{ route('tickets.show', $ticket) }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="mobile-menu-item-0">Leggi / Rispondi</a>
								@can('edit users')
									<a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="mobile-menu-item-1">Chiudi</a>
								@endcan
							</div>
						</div>
					</div>
				</div>

			@endforeach
		</div>

		<footer class="mt-10">
			<div class="pagination py-10">
				@if ($tickets->count() == 0)
					<p>Non ci sono ticket</p>
					
				@else
					{{ $tickets->links() }}
				@endif
			</div>
		</footer>
	</div>
@endsection