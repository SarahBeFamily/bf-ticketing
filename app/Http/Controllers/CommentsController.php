<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Models\Comment;

class CommentsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the request...
        $request->validate([
            'content' => 'required',
            'ticket_id' => 'required|exists:tickets,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $comment = Comment::create($request->all());

        // if ($request->has('file')) {
        //     $ticket->addMedia($request->file)->toMediaCollection('tickets');
        // }

        // Check if tickets has been saved correctly
        if (!$comment) {
            return Redirect::route('tickets.show')->with('error', 'Errore durante l\'invio del commento. Riprova!');
        } else {
            // TODO: Send email to assigned users          
            return Redirect::route('tickets.show', $request->ticket_id)->with('success', 'Commento inviato con successo!');
        }
    }
}
