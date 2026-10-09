<?php

namespace App\Notifications;

use App\Models\Doctor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class DoctorDocumentExpiryNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Doctor $doctor,
        public string $document,
        public int $days,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'title' => 'Doctor document expiry reminder',
            'body' => sprintf(
                '%s %s expires in %d day(s).',
                $this->doctor->name,
                $this->document,
                $this->days,
            ),
            'doctor_id' => $this->doctor->id,
        ]);
    }
}
