<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Blog / notlar
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('title');
            $table->json('excerpt');
            $table->json('body');
            $table->json('tags')->nullable();
            $table->string('cover')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
        });

        // Site içi görüşme takvimi
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->dateTime('starts_at')->index();
            $table->unsignedSmallInteger('duration')->default(30);
            $table->string('name');
            $table->string('email');
            $table->string('topic')->nullable();
            $table->text('note')->nullable();
            $table->string('locale', 5)->default('tr');
            $table->string('status', 20)->default('pending'); // pending | confirmed | cancelled
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });

        // Panelden düzenlenen site ayarları (CV, takvim, analitik)
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        // Proje galerisi ve görüntülenme sayısı
        Schema::table('projects', function (Blueprint $table) {
            $table->json('gallery')->nullable()->after('image');
            $table->unsignedInteger('views')->default(0)->after('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('projects', fn (Blueprint $table) => $table->dropColumn(['gallery', 'views']));
        Schema::dropIfExists('settings');
        Schema::dropIfExists('meetings');
        Schema::dropIfExists('posts');
    }
};
