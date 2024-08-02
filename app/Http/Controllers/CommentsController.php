<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Models\Comment;
use App\Models\Attachment;

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
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                // Upload file to public/uploads folder
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $filePath = $file->move(public_path('uploads'), $fileName);
                $media = Attachment::create([
                    'filename' => $fileName,
                    'path' => $filePath,
                    'mime_type' => $file->getMimeType(), // 'image/jpeg'
                    'size' => $file->getSize(),
                    'ticket_id' => $request->ticket_id,
                    'user_id' => auth()->user()->id,
                    'project_id' => $request->project_id,
                ]);

                if (!$media) {
                    return Redirect::route('tickets.show', $request->ticket_id)->with('error', 'Errore durante il salvataggio del file!');
                }
            }

            // TODO: Send email to assigned users          
            return Redirect::route('tickets.show', $request->ticket_id)->with('success', 'Commento inviato con successo!');
        }
    }
}
