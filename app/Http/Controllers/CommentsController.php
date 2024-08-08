<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Models\Comment;
use App\Models\Attachment;
use App\Models\Notification;
use App\Models\User;
use App\Models\Ticket;

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
            'file.*' => 'mimes:pdf,png,jpg,doc,pdf,docx,pptx,zip|max:8048',
        ]);

        $comment = Comment::create($request->all());

        // Check if comment has been saved correctly
        if (!$comment) {
            return Redirect::route('tickets.show')->with('error', 'Errore durante l\'invio del commento. Riprova!');
        } else {
            // Save the file
            if ($request->hasFile('file')) {
                $f = $request->file('file');
                $fileName = time() . '_' . str_replace(' ', '-', $f->getClientOriginalName());
                $fileSize = $f->getSize();
                $filePath = $f->storeAs('attachments', $fileName, 'public_uploads');
                $media = Attachment::create([
                    'filename' => $fileName,
                    'path' => $filePath,
                    'mime_type' => $f->getClientMimeType(),
                    'size' => $fileSize,
                    'ticket_id' => $ticket->id,
                    'user_id' => auth()->user()->id,
                    'project_id' => $request->project_id,
                ]);

                if (!$media) {
                    return Redirect::route('tickets.show', $request->ticket_id)->with('error', 'Errore durante il salvataggio del file!');
                }
            }

            $mittente = User::find($request->user_id);
            $ticket = Ticket::find($request->ticket_id);

            if ($mittente->hasRole('customer')) {
                // Notifica al referente
                $assigned_to = $ticket->getAssignedToAttribute();

                if (is_array($assigned_to)) {
                    foreach ($assigned_to as $user) {
                        $referente = User::find($user);
                        Notification::create([
                            'user_id' => $referente->id,
                            'data' => sprintf('%s ha risposto al ticket #%d - %s', $mittente->name, $request->ticket_id, $ticket->subject),
                            'notifiable_type' => 'Risposta Ticket',
                        ]);
                    }
                } else {
                    $referente = User::find($assigned_to);

                    Notification::create([
                        'user_id' => $referente->id,
                        'data' => sprintf('%s ha risposto al ticket #%d - %s', $mittente->name, $request->ticket_id, $ticket->subject),
                        'notifiable_type' => 'Risposta Ticket',
                    ]);
                }
            } else {
                // Notifica al cliente
                Notification::create([
                    'user_id' => $ticket->user_id,
                    'data' => sprintf('%s ha risposto al ticket #%d - %s', $mittente->name, $request->ticket_id, $ticket->subject),
                    'notifiable_type' => 'Risposta Ticket',
                ]);
            }
   
            return Redirect::route('tickets.show', $request->ticket_id)->with('success', 'Commento inviato con successo!');
        }
    }
}
