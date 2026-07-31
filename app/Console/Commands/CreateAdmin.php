<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'dapos:create-admin
                            {                            --email=dapos.desktop@gmail.com : Alamat email admin}
                            {--name=Administrator : Nama tampilan admin}';

    protected $description = 'Membuat atau memperbarui akun admin untuk login DAPOS';

    public function handle(): int
    {
        $email = $this->option('email');
        $name = $this->option('name');

        $password = $this->secret('Password untuk admin (default: dapos2026)') ?: 'dapos2026';

        $user = User::updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make($password)]
        );

        $this->info("Akun admin siap: {$user->email} / {$password}");

        return self::SUCCESS;
    }
}
