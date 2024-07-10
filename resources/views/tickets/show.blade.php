@extends('layouts.app')

@section('content')

	@php
		$id = request()->route('ticket');
		$ticket = App\Models\Ticket::find($id);
		$project = App\Models\Project::find($ticket->project_id);
		$customer = App\Models\User::find($project->user_id) ?? null;
		$user = App\Models\User::find($project->assigned_to) ?? null;
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
	<div class="my-20 block">{{ $ticket->content }}</div>

	<div class="commenti block">
		<h2 class="block">Commenti</h2>
		@if ($ticket->getComments()->count() == 0 || $ticket->getComments() == null)
			<p class="mb-10">Non ci sono commenti</p>
			
		@else
			<div class="wrap">
				@foreach ($ticket->getComments() as $comment)
					<div class="comment my-1 bg-accent-25 border-current p-2">
						<p>{{ $comment->content }}</p>
						<p>Scritto da: {{ $comment->user->name }}</p>
					</div>
				@endforeach
			</div>
		@endif
	</div>

	<div class="actions">
		<h2 class="block">Rispondi</h2>
		<form action="{{ route('comments.store', $id) }}" method="POST">
			@csrf
			<div class="form-group">
				<label for="content" class="block">Commento</label>
				<textarea name="content" id="content" class="form-control" @required(true)></textarea>
			</div>

			<div class="form-group my-4">
				<label for="file" class="block">Allega un file</label>
				<input type="file" name="file" id="file">
			</div>

			<input type="hidden" name="ticket_id" value="{{ $id }}">
			<input type="hidden" name="user_id" value="{{ auth()->id() }}">
			<button type="submit" class="button btn-primary">Invia</button>
		</form>
	</div>

@endsection