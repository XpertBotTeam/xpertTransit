<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::create([
            'name'  => 'jorj',
            'email' => 'georgesjarrouj3@gmai.com',
            'phone' => '34342424234',
            "password" => bcrypt('123123123')
        ]);

        dd($user);
    }
}
