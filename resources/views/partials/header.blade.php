<header class="bg-secondary">
	<div class="container mx-auto px-4">
		<div class="flex justify-between items-center py-4">
			<a href="{{ route('dashboard') }}">
				<img src="{{ Vite::asset('resources/images/bf-logo-reg-small.png') }}" alt="Be.Family Assistenza Clienti" class="h-12">
			</a>

			@include('../layouts/navigation')
		</div>
	</div>
</header>