<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;

class DeleteExpiredUsers extends Command
{
    protected $signature = 'delete:expired_users';

    protected $description = 'Delete user data for users whose tokens have expired.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Calculate the expiration date 30 days after the token issuance
        $expirationDate = Carbon::now()->subDays(30);

        // Query users whose tokens were issued more than 15 days ago
        $users = User::where('created_at', '<', Carbon::now()->subDays(30))->get();

        foreach ($users as $user) {
            // Check if the user's token has not been refreshed for more than 30 days
            if ($user->updated_at < $expirationDate) {
                // Delete the user and associated data
                $user->delete();
                $this->info('User with ID ' . $user->id . ' has been deleted.');
            }
        }
        $this->info('Expired users deletion completed.');
    }
}
