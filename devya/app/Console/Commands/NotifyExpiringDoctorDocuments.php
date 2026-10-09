<?php

namespace App\Console\Commands;

use App\Models\Doctor;
use App\Models\User;
use App\Notifications\DoctorDocumentExpiryNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class NotifyExpiringDoctorDocuments extends Command
{
    protected $signature = 'doctors:notify-expiring-documents';

    protected $description = 'Notify administrators about expiring doctor documents';

    public function handle(): int
    {
        $today = Carbon::today();
        $until = $today->copy()->addDays(30);

        $recipients = User::query()
            ->role(['super_admin', 'admin'])
            ->where('is_active', true)
            ->get();

        Doctor::query()
            ->where('is_active', true)
            ->where(function ($query) use ($today, $until): void {
                $query
                    ->whereBetween('slmc_document_expiry', [$today, $until])
                    ->orWhereBetween('nic_document_expiry', [$today, $until]);
            })
            ->get()
            ->each(function (Doctor $doctor) use ($recipients, $today, $until): void {
                foreach ([
                    'SLMC registration document' => $doctor->slmc_document_expiry,
                    'NIC / passport document' => $doctor->nic_document_expiry,
                ] as $document => $expiryValue) {
                    if (! $expiryValue) {
                        continue;
                    }

                    $expiry = Carbon::parse((string) $expiryValue);

                    if ($expiry->isBefore($today) || $expiry->isAfter($until)) {
                        continue;
                    }

                    $days = (int) $today->diffInDays($expiry);

                    foreach ($recipients as $recipient) {
                        $recipient->notify(
                            new DoctorDocumentExpiryNotification(
                                $doctor,
                                $document,
                                $days
                            )
                        );
                    }
                }
            });

        $this->info('Doctor document expiry notifications sent.');

        return self::SUCCESS;
    }
}
