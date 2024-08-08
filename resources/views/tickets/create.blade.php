@extends('layouts.app')

@section('content')

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

	<div class="mb-10">
		<h1>Nuovo Ticket</h1>
		<p>Apri un nuovo ticket riferito ad un progetto</p>
	</div>
	
	<form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
		@csrf

		<div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
			<div class="form-group sm:col-span-full relative">
				<label for="subject" class="block text-sm font-medium leading-6 text-gray-900">Oggetto</label>
				<input class="form-control block w-full" type="text" name="subject" id="subject" value="{{ old('subject') }}" @required(true)>
				
				@error('subject')
					<div class="absolute left-full -mt-7 -ml-8">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="text-accent-100 w-5 h-5"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>
					</div>
					<p class="text-error">{{ $message }}</p>
				@enderror
			</div>
			
			<div class="form-group max-sm:col-span-full col-span-3">
				<label for="type" class="block text-sm font-medium leading-6 text-gray-900">Tipologia</label>
				<select name="type" id="type" class="form-control block w-full">
					<option value="">Seleziona una tipologia</option>
					@if (is_array($settings->get('ticket_type')))
						@foreach ($settings->get('ticket_type') as $type)
							<option value="{{ $type }}">{{ $type }}</option>
						@endforeach
					@else
						<option value="Bug">Bug</option>
						<option value="Richiesta">Richiesta</option>
						<option value="Altro">Altro</option>
					@endif
				</select>
			</div>
	
			@if (!$ticket->project_id)
				<div class="form-group max-sm:col-span-full col-span-3">
					<label for="project_id" class="block text-sm font-medium leading-6 text-gray-900">Progetto di riferimento</label>
					<select name="project_id" id="project_id" class="form-control block w-full">
						<option value="">Seleziona un progetto</option>
						@foreach ($projects as $project)
							<option value="{{ $project->id }}">{{ $project->name }}</option>
						@endforeach
					</select>
				</div>
			@else
				<input type="hidden" name="project_id" value="{{ $ticket->project_id }}">
			@endif
	
			<div class="form-group col-span-full">
				<label for="content" class="block text-sm font-medium leading-6 text-gray-900">Messaggio</label>
				<textarea name="content" id="content" class="form-control block w-full">{{ old('content') }}</textarea>
			</div>
	
			{{-- <div class="basis-full">
				<label for="attachment" class="block text-sm font-medium leading-6 text-gray-900">Allegati</label>
				<div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
				  <div class="text-center">
					<svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd"></path>
					</svg>
					<div class="mt-4 flex text-sm leading-6 text-gray-600">
					  <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
						<span>Allega un file</span>
						<input id="file-upload" name="file-upload" type="file" class="sr-only">
					  </label>
					  <p class="pl-1">or drag and drop</p>
					</div>
					<p class="text-xs leading-5 text-gray-600">PNG, JPG, PDF, webp, webm fino a 3MB</p>
				  </div>
				</div>
			</div> --}}
			<div class="form-group my-4 col-span-full">
				<label for="file" class="block text-sm font-medium leading-6 text-gray-900">Aggiungi allegati</label>
				<input 
                        type="file" 
                        name="file[]" 
                        id="inputFile"
                        class="form-control block w-full @error('file') is-invalid @enderror" multiple>
      
                    @error('file')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
			</div>

			<input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

		</div>

		<button type="submit" class="button btn-primary mt-5 block">Invia</button>
	</form>
@endsection