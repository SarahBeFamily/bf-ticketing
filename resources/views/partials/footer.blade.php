<footer class="bg-secondary">
	
	<div class="container mx-auto py-4 px-4 flex items-center justify-between text-white">
		<p>
			Copyright &copy; {{ date('Y') }} <a href="https://befamily.it">Be.Family</a>
		</p>

		<p>
			@can('edit users', App\Models\User::class)
				<a href="{{ route('settings.index') }}">Impostazioni</a> | 
			@endcan
		
			<a href="{{ route('profile.notifications') }}">Notifiche</a>
		</p>
	</div>
</footer>