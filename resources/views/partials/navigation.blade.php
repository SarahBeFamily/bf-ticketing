<div class="menu" style="background-color: red;">
	<ul>
		@can('team')
			<li><a href="{{ route('clienti') }}">Clienti</a></li>
		@endcan
		<li><a href="{{ route('dashboard') }}">Bacheca</a></li>
		<li><a href="#">Progetti</a></li>	
	</ul>
</div>