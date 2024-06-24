@php
	/**
	 * Log in page
	 * /login
	 */
@endphp

@extends('layouts.app')

@section('content')
	<div class="container mx-auto">
		<div class="flex justify-center">
			<div class="w-8/12 bg-white p-6 rounded-lg">
				Login
				<form action="{{ route('accedi') }}" method="post">
					<div class="mb-4">
						<label for="email" class="sr-only">Email</label>
						<input type="text" name="email" id="email" placeholder="Your email" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
					</div>
					<div class="mb-4">
						<label for="password" class="sr-only">Password</label>
						<input type="password" name="password" id="password" placeholder="Choose a password" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
					</div>
					<div>
						<button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">Login</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection