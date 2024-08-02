@extends('layouts.app')

@section('content')
    <div class="dashboard">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Dashboard</h1>
                    @can('edit users', App\Models\User::class)
                        <img class="h-12 w-12 rounded-full" src="{{ Auth::user()->avatar() }}" alt="{{ Auth::user()->name }}">
                    @endcan
                    <p>Benvenuto {{ Auth::user()->name }}</p>
                </div>

                <div class="col-md-6">
                    <h2>Numeri e statistiche</h2>
                    <p>Di seguito trovi alcune statistiche relative ai progetti, ai clienti e ai ticket aperti.</p>

                    <div class="container">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card mb-5 flex content-center">
                                    <div class="card-body">
                                        <h3 class="card-title">Progetti</h3>
                                        <p class="card-text">Numero di progetti attivi: {{ $projects->count() }}</p>
                                    </div>

                                </div>
        
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title">Clienti</h3>
                                        <p class="card-text">Numero di clienti: {{ $customers->count() }}</p>
                                    </div>

                                </div>
        
                                <div class="card">
                                    <div class="card-body">
                                        @php
                                            $open_tickets = $tickets->where('status', 'Aperto');
                                        @endphp
                                        <h3 class="card-title">Ticket</h3>
                                        <p class="card-text">Numero di ticket: {{ $tickets->count() }}</p>
                                        <p class="card-text">Numero di ticket aperti: {{ $open_tickets->count() }}</p>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                </div>
            </div>
        </div>
    </div>
@endsection