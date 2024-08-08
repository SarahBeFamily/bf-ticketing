<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;

class NotificationMarkAllAsRead extends Component
{
    public function render()
    {
        return view('livewire.notification-mark-all-as-read');
    }

    /**
     * Mark the notification as read.
     *
     * @param [int] $key | Notification ID
     * @return void
     */
    public function markAllAsRead()
    {
        $notification = Notification::all();
        
        foreach ($notification as $notify) {
            $status = $notify->status;
            if ($status === 'unread') {
                $notify->markAsRead();
            }

            if ($notify->status === 'unread') {
                session()->flash('error', __('Errore durante la segnalazione della notifica come letta.'));

                $this->redirectRoute('profile.notifications');
            }
        }

        session()->flash('success', __('Notifiche segnalate come lette.'));

        $this->redirectRoute('profile.notifications');
    }
}

