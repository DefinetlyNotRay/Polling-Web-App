<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ChangeUserPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:change-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change all user passwords to bcrypt hashed passwords';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Retrieve all users
        $users = User::all();

        // Loop through each user and update their password to bcrypt hash
        foreach ($users as $user) {
            $user->password = bcrypt('admin'); // You can set a default new password here
            $user->save();
        }

        $this->info('All user passwords have been changed to bcrypt hashed passwords.');

        return 0;
    }
}