<?php

namespace App\Console\Commands;

use App\Actions\Notification\SendFcmNotificationAction;
use Illuminate\Console\Command;

class TestFcmConnectionCommand extends Command
{
    protected $signature = 'fcm:test 
                            {--token= : Optional target FCM token} 
                            {--dry-run : Validate connection with Firebase without sending to device}';

    protected $description = 'Test Firebase Cloud Messaging (FCM) authentication and API connection';

    public function handle(SendFcmNotificationAction $sendFcmAction): int
    {
        $this->info('Initializing FCM Connection Test...');

        $token = $this->option('token') ?: 'dry-run-test-token-device-xyz';
        $dryRun = $this->option('dry-run') || !$this->option('token');

        $this->line("Target Project ID: <comment>" . config('services.firebase.project_id') . "</comment>");
        $this->line("Credentials File: <comment>" . config('services.firebase.credentials') . "</comment>");
        $this->line("Mode: <comment>" . ($dryRun ? 'Dry Run (validate_only)' : 'Live Dispatch') . "</comment>");

        $result = $sendFcmAction->execute(
            $token,
            'Test Push Notification',
            'Sistem backend berhasil terhubung ke Firebase Cloud Messaging!',
            ['test_id' => (string) time()],
            $dryRun
        );

        $errorStr = $result['error'] ?? '';
        $isUnregisteredOrInvalidToken = str_contains($errorStr, 'UNREGISTERED') || str_contains($errorStr, 'NotRegistered') || str_contains($errorStr, 'INVALID_ARGUMENT');

        if (($result['success'] ?? false) || ($dryRun && $isUnregisteredOrInvalidToken)) {
            $this->info("\n✅ SUCCESS: Backend authentication & connection to Firebase FCM API verified!");
            $this->line("Google OAuth2 Service Account authenticated successfully.");
            $this->line("Firebase FCM Server Project ID: <info>" . config('services.firebase.project_id') . "</info>");
            if ($isUnregisteredOrInvalidToken) {
                $this->comment("Notice: Firebase API reached & validated request structure (Token is not bound to a real device yet, which is expected for dry run).");
            }
            return self::SUCCESS;
        }

        $this->error("\n❌ FAILED: Connection to Firebase FCM API failed.");
        $this->line($errorStr ?: 'Unknown error occurred.');
        return self::FAILURE;
    }
}
