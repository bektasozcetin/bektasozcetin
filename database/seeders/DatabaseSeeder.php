<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin kullanıcısı .env içindeki ADMIN_EMAIL / ADMIN_PASSWORD ile oluşturulur.
        if (env('ADMIN_EMAIL') && env('ADMIN_PASSWORD')) {
            User::updateOrCreate(
                ['email' => env('ADMIN_EMAIL')],
                ['name' => env('ADMIN_NAME', 'Admin'), 'password' => env('ADMIN_PASSWORD')],
            );
        }

        $this->call([PortfolioSeeder::class, PostSeeder::class]);
    }
}
