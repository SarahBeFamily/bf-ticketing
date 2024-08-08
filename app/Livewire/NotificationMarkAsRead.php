<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;

class NotificationMarkAsRead extends Component
{
    public $key;

    public function mount($key)
    {
        $this->key = $key;
    }
    
    public function render()
    {
        return view('livewire.notification-mark-as-read');
    }

    /**
     * Mark the notification as read.
     *
     * @param [int] $key | Notification ID
     * @return void
     */
    public function markAsRead($key)
    {
        $notification = Notification::find($key);
        $status = $notification ? $notification->status : null;

        if ($status === 'unread') {
            $notification->markAsRead();
        }

        // Check if the notification is read
        if ($notification->status === 'read') {
            session()->flash('success', __('Notifica segnalata come letta.'));
        } else {
            session()->flash('error', __('Errore durante la segnalazione della notifica come letta.'));
        }

        $this->redirectRoute('profile.notifications');
    }
}
