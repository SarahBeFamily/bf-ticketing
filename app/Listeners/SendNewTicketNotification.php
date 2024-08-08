<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendNewTicketNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TicketCreated $event): void
    {
        // Send email to assigned users
        $users = $event->ticket->assignedUsers;
        foreach ($users as $user) {
            // Send email to assigned users
            Mail::to($user->email)->send(new TicketCreatedMail($event->ticket));
        }
    }
}
