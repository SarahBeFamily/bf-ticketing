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

	<header class="tickets flex items-center justify-between">
		<div class="basis-2/4">
			<h1 class="mb-5">{{ __('Notifiche') }}</h1>
			<div class="mr-4 text-sm text-gray-500 block">
				{{ sprintf(__('Hai %d notifiche'), $notifications->count()) }}
			</div>
		</div>

		<div class="flex items-center">
			<div class="mr-4 text-sm text-gray-500">
				{{ sprintf(__('Hai %d nuove notifiche non lette'), $unread_nots->count()) }}
			</div>
			@livewire('notification-mark-all-as-read')
			
			<form action="{{ route('profile.notificationDestroy') }}" method="POST">
				@csrf
				@method('DELETE')
				<button type="submit" class="button bg-secondary ml-1 hover:bg-grey-800 text-white px-4 py-2 rounded-md">
					{{ __('Elimina tutte') }}
				</button>
			</form>
		</div>
	</header>

	<div class="py-12">
		<div class="mx-auto sm:px-6 lg:px-8">
			
			@if ($notifications->isEmpty())
				Non ci sono notifiche.
			@endif

			@foreach ($notifications->reverse() as $i => $notification)
				@php
					$bg = $i % 2 == 0 ? 'bg-gray-100 ' : '';
				@endphp
				<div class="{{ $bg }}lg:flex lg:items-center lg:justify-between border-b border-gray-300 p-5">
					<div class="">
						<div class="flex items-center">
							<div class="font-bold">{{ $notification->created_at->format('d/m/Y H:i') }}</div>
							<div class="ml-4 text-sm text-gray-500">
								{{ $notification->created_at->diffForHumans() }}
							</div>
						</div>
						
						<div class="block">
							{{ $notification->data }}
						</div>
					</div>
				
					<div class="flex items-center">
						@if($notification->status === 'unread')
							@livewire('notification-mark-as-read', ['key' => $notification->id])
						@endif
						{{-- elimina notifica --}}
						<form action="{{ route('profile.notificationDestroySingle', $notification->id) }}" method="POST">
							@csrf
							@method('DELETE')
							<button type="submit" class="button bg-secondary ml-1 hover:bg-grey-800 text-white px-4 py-2 rounded-md">
								{{ __('Elimina') }}
							</button>
						</form>
					</div>
				</div>
				
			@endforeach
		
		</div>
	</div>

@endsection
