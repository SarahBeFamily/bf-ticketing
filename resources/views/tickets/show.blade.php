@extends('layouts.app')

@section('content')

	@php
		$id = request()->route('ticket');
		// $ticket = App\Models\Ticket::find($id);
		// $project = App\Models\Project::find($ticket->project_id);
		// $company = $project ? App\Models\Company::find($project->company_id) : null;
		// $customer = $ticket ? App\Models\User::find($ticket->user_id) : null;
		// $user = $project ? App\Models\User::find($project->assigned_to) : null;
		$attchments = $ticket->getAttachments();
	@endphp

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

	<h1> {{ $ticket->subject }}</h1>
	<div class="info">
		Creato da {{ $customer->name }} il {{ $ticket->created_at->format('d/m/Y') }} alle ore: {{ $ticket->created_at->format('H:i') }} |
	</div>
	<div class="mt-5 mb-10 block">
		{{ $ticket->content }}

		@if ($attchments->count() > 0 )
			<div class="allegati mt-10">
				<h2 class="block">Allegati</h2>
				<ul>
					@foreach ($attchments as $attachment)
						<li>
							<img class="block open-image cursor-pointer" src="{{ $attachment->path }}" alt="{{ $attachment->filename }}">
						</li>
					@endforeach
				</ul>
			</div>
		@endif
	</div>

	<div class="commenti block">
		<h2 class="block">Commenti</h2>
		@if ($ticket->getComments()->count() == 0 || $ticket->getComments() == null)
			<p class="mb-10">Non ci sono commenti</p>
			
		@else
			<div class="wrap">
				@foreach ($ticket->getComments() as $comment)
					<div class="comment my-1 bg-gray-100 border-current p-2">
						<p class="mb-5">{{ $comment->content }}</p>
						<p class="text-xs">Scritto da: {{ $comment->user->name }} | il: {{ $comment->created_at->format('d/m/Y') }} alle ore: {{ $comment->created_at->format('H:i') }} </p>
					</div>
				@endforeach
			</div>
		@endif
	</div>

	<div class="actions">
		<h2 class="block">Rispondi</h2>
		<form action="{{ route('comments.store', $id) }}" method="POST" enctype="multipart/form-data">
			@csrf

			<div class="form-group">
				<label for="content" class="block">Commento</label>
				<textarea name="content" id="content" class="form-control" @required(true)></textarea>
			</div>

			<div class="form-group my-4">
				<label for="file" class="block">Allega un file</label>
				<input 
                        type="file" 
                        name="file" 
                        id="inputFile"
                        class="form-control @error('file') is-invalid @enderror">
      
                    @error('file')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
			</div>

			<input type="hidden" name="ticket_id" value="{{ $id }}">
			<input type="hidden" name="user_id" value="{{ auth()->id() }}">
			<input type="hidden" name="project_id" value="{{ $project->id }}">
			<button type="submit" class="button btn-primary">Invia</button>
		</form>
	</div>

	<div id="modal-image" class="hidden fixed top-0 left-0 w-full h-screen bg-opacity-80 bg-secondary">
		<div class="modal-body flex flex-col justify-center items-center min-h-screen">
			<span class="close-modal bg-white cursor-pointer">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-x">
					<path stroke="none" d="M0 0h24v24H0z" fill="none" />
					<path d="M18 6l-12 12" />
					<path d="M6 6l12 12" />
				  </svg>
			</span>
			<img src="" alt="" class="w-full h-auto max-w-fit mb-4 border-white border-solid border-8">
			<a href="" class="button btn-primary" target="_blank" download="">{{ __('Scarica') }}</a>
		</div>
	</div>

@endsection