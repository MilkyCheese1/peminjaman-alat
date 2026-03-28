<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SetupOwner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:owner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup owner user account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check jika owner sudah ada
        $existingOwner = User::where('role', 'owner')->first();

        if ($existingOwner) {
            $this->error("❌ Owner sudah ada: {$existingOwner->username}");
            return 1;
        }

        // Create owner user
        $owner = User::create([
            'username' => 'owner',
            'email' => 'owner@peminjaman.local',
            'phone' => '081234567890',
            'password' => Hash::make('owner123'),
            'role' => 'owner',
            'alamat' => 'Kantor Pusat',
            'email_verified' => true,
            'is_active' => true,
        ]);

        $this->info('✅ Owner account berhasil dibuat!');
        $this->info('');
        $this->info('Details:');
        $this->info("- Username: {$owner->username}");
        $this->info("- Email: {$owner->email}");
        $this->info("- Role: {$owner->role}");
        $this->info("- ID: {$owner->id_user}");
        $this->info('');
        $this->info('Login credentials:');
        $this->info('- Username: owner');
        $this->info('- Password: owner123');
        $this->info('');
        $this->info('Owner dapat:');
        $this->info('- Melihat semua data');
        $this->info('- Melihat semua activity logs');
        $this->info('- Restore dan delete permanent trash');
        $this->info('- Manage semua resource');

        return 0;
    }
}
