<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Models\Ticket;
use App\Models\Project;
use App\Models\User;
use App\Models\Attachment;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\QueryBuilder;
use App\Notifications\TicketCreated;
use Spatie\QueryBuilder\AllowedFilter;

class TicketsController extends Controller
{
    
    /**
     * Display a listing of the projects.
     * Passing data to the view
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter') ? $request->query('filter') : 'all';
        $per_page = $request->query('per_page') ? $request->query('per_page') : 15;
        $page = $request->page ? $request->page : 1;
        $currentUser = auth()->user();
        $customers = User::role('customer')->get();

        // If customer, show only projects assigned to the user
        // If team, show all projects
        if ($currentUser->hasRole('customer')) {
            $projects = Project::where('user_id', $currentUser->id);
        } else {
            $projects = Project::all();
        }

        // Query the tickets
        // Cheeck if user is customer or team
        if ($currentUser->hasRole('customer')) {
            $tickets = QueryBuilder::for(Ticket::class)
                ->where('user_id', $currentUser->id)
                ->allowedIncludes(['projects'])
                ->allowedFilters(['status', 'division', 'assigned_to', 'project_id'])
                ->allowedSorts('order', 'name', 'created_at', 'updated_at', 'completed_at')
                ->paginate($per_page, ['*'], 'page', $page)
                ->appends(request()->query());
        } else {
            $tickets = QueryBuilder::for(Ticket::class)
                ->allowedIncludes(['projects', 'customers'])
                ->allowedFilters(['status', 'division', 'assigned_to', 'project_id', 'user_id'])
                ->allowedSorts('order', 'name', 'created_at', 'updated_at', 'completed_at')
                ->paginate($per_page, ['*'], 'page', $page)
                ->appends(request()->query());
        }
        
        return view('tickets.index', compact('tickets', 'filter', 'projects', 'customers', 'per_page', 'page'));
    }

    /**
     * Enable sorting of the projects.
     */
    public function sort(Request $request)
    {
        $tickets = Ticket::orderBy($request->sort_by, $request->sort_order)->paginate(15);
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Enable filtering of the projects.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function filter(Request $request): RedirectResponse
    {
        $slug = [];
        
        if ($request->has('project_id') && $request->project_id != '') {
            $slug[] = 'filter[project_id]='.$request->project_id;
        }

        if ($request->has('division') && $request->division != '') {
            $slug[] = 'filter[division]='.$request->division;
        }

        if ($request->has('status') && $request->status != '') {
            $slug[] = 'filter[status]='.$request->status;
        }

        if ($request->has('user_id') && $request->user_id != '') {
            $slug[] = 'filter[user_id]='.$request->user_id;
        }

        if ($request->has('assigned_to') && $request->assigned_to != '') {
            $slug[] = 'filter[assigned_to]='.$request->assigned_to;
        }

        $slug = implode('&', $slug);

        return Redirect::route('tickets.index', $slug);
    }

    /**
     * Paginate the tickets.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function paginate(Request $request)
    {
        $page = $request->page;
        $user = auth()->user();

        if ($user->hasRole('customer')) {
            $tickets = Ticket::where('user_id', $user->id)->paginate(15, ['*'], 'page', $page);
        } else {
            $tickets = Ticket::paginate(15, ['*'], 'page', $page);
        }

        return $tickets;
    }

    /**
     * Show the form for creating a new project.
     * Passing data to the view
     */
    public function create(Request $request)
    {
        $ticket = new Ticket();
        $ticket->project_id = $request->project_id ?? null;
        $projects = Project::where('user_id', auth()->user()->id)->get();
        return view('tickets.create', compact('ticket', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the request...
        $request->validate([
            'subject' => 'required|max:255',
            'content' => 'required',
            'project_id' => 'required|exists:projects,id',
            'type' => 'required',
            'user_id' => 'required|exists:users,id',
            'file.*' => 'mimes:pdf,png,jpg,doc,pdf,docx,pptx,zip|max:8048',
        ]);

        // Handle error
        if (auth()->user()->hasRole('customer') && $request->user_id != auth()->user()->id) {
            return Redirect::route('tickets.create')->with('error', 'Non hai i permessi per aprire un ticket per un altro utente!');
        }

        $project_rel = Project::find($request->project_id);

        $ticket = Ticket::create(
            array_merge(
            $request->only([
                'subject',
                'content',
                'project_id',
                'type',
                'user_id',
            ]), [
                'status' => 'Aperto',
                'assigned_to' => implode(',',$project_rel->assigned_to),
                'division' => $project_rel->division,
                ]
            )
        );


        // Check if tickets has been saved correctly
        if (!$ticket) {
            return Redirect::route('tickets.create')->with('error', 'Errore durante la creazione del ticket!');
        } else {
            // Save the file if exists or if is an array of files
            if ($request->hasFile('file')) { 
                $files = $request->file('file');

                foreach ($files as $f) {
                    $fileName = time() . '_' . $f->getClientOriginalName();
                    $filePath = $f->storeAs('uploads', $fileName, 'public');
                    $filePath = $file->move(public_path('uploads'), $fileName);
                    $media = Attachment::create([
                        'filename' => $fileName,
                        'path' => $filePath,
                        'mime_type' => $f->getMimeType(), // 'image/jpeg'
                        'size' => $f->getSize(),
                        'ticket_id' => $ticket->id,
                        'user_id' => auth()->user()->id,
                        'project_id' => $request->project_id,
                    ]);

                    if (!$media) {
                        return Redirect::route('tickets.create')->with('error', 'Errore durante il salvataggio del file!');
                    }
                }
            }
            
            if (is_array($project_rel->assigned_to) && count($project_rel->assigned_to) > 0) {
                foreach ($project_rel->assigned_to as $assigned_to) {
                    $team_email = User::find($assigned_to)->email;
                    $message = 'Un nuovo ticket è stato aperto da '.User::find($ticket->user_id)->name.' per il progetto '.Project::find($ticket->project_id)->name.'.';
                    // TODO: Send email to the team
                    // Mail::to($team_email)->send(new TicketCreated($ticket, $message));

                    // To DO: Send email to the customer
                    // Mail::to(User::find($ticket->user_id)->email)->send(new TicketCreated($ticket));

                    // Send notification
                    User::find($assigned_to)->notify(new TicketCreated($ticket, $message));
                }
            }
            
            return Redirect::route('tickets.index', $ticket)->with('success', 'Ticket aperto, il nostro team ti risponderà al più presto!');
        }
    }

    /**
     * Display the specified project.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        return view('tickets.show');
    }

    /**
     * Close the specified ticket.
     * 
     * @param int $id
     * @return RedirectResponse
     */
    public function close(Request $request): RedirectResponse
    {
        $ticket = Ticket::find($request->ticket);
        if ($ticket->status == 'Aperto') {
            $ticket->update([
                'status' => 'Chiuso',
                'completed_at' => now(),
            ]);

            // To DO: Send email to the customer
            // Mail::to(User::find($ticket->user_id)->email)->send(new TicketClosed($ticket));
            return Redirect::route('tickets.index')->with('success', 'Ticket chiuso con successo!');
        }           
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        // Only team can delete projects
        if (!auth()->user()->hasRole('team')) {
            return Redirect::route('tickets.index')->with('error', 'Non hai i permessi per eliminare questo ticket!');
        } else {
            $ticket = Ticket::find($id);
            $ticket->delete();
            return redirect()->route('tickets.index')->with('success', 'Ticket eliminato con successo!');
        }
    }
}
