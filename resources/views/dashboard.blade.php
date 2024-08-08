@extends('layouts.app')

@section('content')
    <div class="dashboard">
        <div class="container">
            
            <div class="intro py-3">
                <h1>Dashboard</h1>
                <div class="inline-flex items-center">
                    @can('edit users', App\Models\User::class)
                        <img class="h-12 w-12 rounded-full" src="{{ Auth::user()->avatar() }}" alt="{{ Auth::user()->name }}">
                    @endcan
                    <p>Ciao, {{ Auth::user()->name }}!</p>
                </div>
            </div>

            @can('edit users', App\Models\User::class)
                @php
                    $open_tickets = $tickets->whereIn('status', ['Aperto', 'In lavorazione']);
                    $openTicketsAssignedToMe = [];
                    foreach ($open_tickets as $ticket) {
                        if (in_array(Auth::user()->id, $ticket->assigned_to)) {
                            $openTicketsAssignedToMe[] = $ticket;
                        }
                    }
                @endphp
                <div class="grid grid-flow-col auto-cols-max gap-5">
                    <div class="col-span-2">
                        <h2>Prossimi ticket</h2>
                        <p class="pb-2">Di seguito trovi i prossimi ticket a te assegnati, ancora da gestire.</p>
                        <table class="table-auto border-collapse border border-slate-500">
                            <thead>
                                <tr class="bg-secondary text-white">
                                    <th class="text-left py-2 px-4" scope="col">Titolo</th>
                                    <th class="text-left py-2 px-4" scope="col">Azienda</th>
                                    <th class="text-left py-2 px-4" scope="col">Cliente</th>
                                    <th class="text-left py-2 px-4" scope="col">Stato</th>
                                    <th class="text-left py-2 px-4" scope="col">Data Creazione</th>
                                    <th class="text-left py-2 px-4" scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($openTicketsAssignedToMe) == 0)
                                    <tr>
                                        <td colspan="4">Nessun ticket aperto assegnato a te.</td>
                                    </tr>                                    
                                @else
                                    @foreach ($openTicketsAssignedToMe as $ticket)
                                        @php($azienda = App\Models\Company::find($ticket->customer->company))
                                        <tr class="hover:bg-accent-25">
                                            <td class="py-3 px-4">{{ $ticket->subject }}</td>
                                            <td class="py-3 px-4">{{ $azienda ? $azienda->name : '' }}</td>
                                            <td class="py-3 px-4">{{ $ticket->customer->name }}</td>
                                            <td class="py-3 px-4">{{ $ticket->status }}</td>
                                            <td class="py-3 px-4">{{ $ticket->created_at->format('d/m/Y') }}</td>
                                            <td class="py-3 px-4">
                                                <a href="{{ route('tickets.show', $ticket->id) }}" class="underline">Visualizza</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-span-1">
                        <h2>Numeri e statistiche</h2>
                        <p class="pb-2">Di seguito trovi alcune statistiche relative ai progetti, ai clienti e ai ticket aperti.</p>

                        <div class="container">
                            <table class="table-auto border-collapse border border-slate-500">
                                <thead>
                                    <tr class="bg-accent-100 text-white">
                                        <th class="text-left py-2 px-4">Progetti</th>
                                        <th class="text-left py-2 px-4">Ticket</th>
                                        <th class="text-left py-2 px-4">clienti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="py-3 px-4">Numero di progetti attivi: {{ $projects->count() }}</td>
                                        <td class="py-3 px-4">
                                            <p class="card-text">Numero di ticket: {{ $tickets->count() }}</p>
                                            <p class="card-text">Numero di ticket aperti: {{ $open_tickets->count() }}</p>
                                        </td>
                                        <td class="py-3 px-4"><p class="card-text">Numero di clienti: {{ $customers->count() }}</p></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection