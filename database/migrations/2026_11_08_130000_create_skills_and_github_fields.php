<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('group', 20)->index();
            $table->string('name');
            $table->unsignedTinyInteger('level')->default(80);
            $table->unsignedTinyInteger('years')->nullable();
            $table->boolean('is_core')->default(false);
            $table->unsignedSmallInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::table('repositories', function (Blueprint $table) {
            $table->unsignedBigInteger('github_id')->nullable()->unique()->after('id');
            $table->unsignedInteger('forks')->nullable()->after('stars');
            $table->json('topics')->nullable()->after('forks');
            $table->timestamp('pushed_at')->nullable()->after('topics');
        });
    }

    public function down(): void
    {
        Schema::table('repositories', fn (Blueprint $table) => $table->dropColumn(['github_id', 'forks', 'topics', 'pushed_at']));
        Schema::dropIfExists('skills');
    }
};
