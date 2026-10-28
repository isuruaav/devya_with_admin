<?php

namespace App\Console\Commands;

use App\Services\TextLkSmsService;
use Illuminate\Console\Command;

class SendTestSmsCommand extends Command
{
    protected $signature = 'sms:test {phone : Recipient phone number (e.g. 0771234567)} {--message= : Optional test message content}';

    protected $description = 'Send a test SMS message via Text.lk to verify gateway credentials';

    public function handle(TextLkSmsService $smsService): int
    {
        $phone = (string) $this->argument('phone');
        $customMessage = $this->option('message');

        $this->info("Checking Text.lk SMS Gateway configuration...");

        if (! $smsService->isEnabled()) {
            $this->error("Text.lk SMS Gateway is not enabled or API token is missing!");
            $this->warn("Please check your .env file and ensure:");
            $this->line("  TEXTLK_ENABLED=true");
            $this->line("  TEXTLK_API_TOKEN=your_text_lk_api_token_here");
            $this->line("  TEXTLK_SENDER_ID=DEVYA");
            return 1;
        }

        $message = $customMessage ?: "DEVYA CEYLON Test SMS: Your Text.lk SMS Gateway has been configured successfully! Sent at " . now()->format('Y-m-d h:i A');

        $this->info("Sending test SMS to: {$phone}");
        $this->line("Message: {$message}");

        $result = $smsService->send($phone, $message);

        if ($result['success']) {
            $this->info("SUCCESS! SMS sent successfully.");
            if (! empty($result['data'])) {
                $this->line(json_encode($result['data'], JSON_PRETTY_PRINT));
            }
            return 0;
        }

        $this->error("FAILED: " . ($result['message'] ?? 'Unknown error occurred.'));
        return 1;
    }
}
