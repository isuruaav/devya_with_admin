<?php

namespace App\Console\Commands;

use App\Models\Staff;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateStaffQrTokens extends Command
{
    protected $signature = 'staff:generate-qr-tokens';

    protected $description = 'Generate secure QR tokens for staff members';

    public function handle(): int
    {
        $count = 0;

        Staff::withTrashed()
            ->whereNull('qr_token')
            ->chunkById(100, function ($staffMembers) use (&$count): void {

                foreach ($staffMembers as $staff) {

                    $staff->update([
                        'qr_token' => Str::random(64),
                    ]);

                    $count++;
                }
            });

        $this->info("QR tokens generated successfully: {$count}");

        return self::SUCCESS;
    }
}
