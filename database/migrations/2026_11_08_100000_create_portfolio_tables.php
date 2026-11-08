<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
| Çevrilebilir alanlar JSON olarak tutulur: {"tr": "...", "en": "..."}
| Okumak için modellerdeki HasTranslations trait'inin t() metodunu kullan.
*/

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('category')->index(); // web | panel | api
            $table->json('type');
            $table->json('title');
            $table->string('year', 4);
            $table->json('description');
            $table->json('result');
            $table->json('role')->nullable();
            $table->json('duration')->nullable();
            $table->json('problem')->nullable();
            $table->json('approach')->nullable();
            $table->json('lesson')->nullable();
            $table->json('decisions')->nullable(); // [{q: {tr,en}, a: {tr,en}}]
            $table->json('tags')->nullable();
            $table->string('image')->nullable();
            $table->string('live_url')->nullable();
            $table->string('code_url')->nullable();
            $table->unsignedSmallInteger('sort')->default(0);
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('icon'); // app | cart | panel | api | up | gauge
            $table->json('title');
            $table->json('description');
            $table->json('tags')->nullable();
            $table->unsignedSmallInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('repositories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('description');
            $table->json('kind');
            $table->string('language')->default('PHP');
            $table->string('url')->nullable();
            $table->unsignedInteger('stars')->nullable();
            $table->unsignedSmallInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->json('period');
            $table->string('role');
            $table->json('company');
            $table->json('description');
            $table->json('tags')->nullable();
            $table->boolean('is_current')->default(false);
            $table->unsignedSmallInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->json('kind');
            $table->json('title');
            $table->json('subtitle')->nullable();
            $table->unsignedSmallInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('badge')->nullable();
            $table->json('description');
            $table->json('price_label');
            $table->json('price');
            $table->json('items')->nullable(); // [{tr,en}]
            $table->json('cta');
            $table->string('contact_type')->default('web');
            $table->boolean('is_featured')->default(false);
            $table->unsignedSmallInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->json('quote');
            $table->string('name');
            $table->json('title');
            $table->string('photo')->nullable();
            $table->unsignedSmallInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->json('question');
            $table->json('answer');
            $table->unsignedSmallInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('project_type')->nullable();
            $table->text('message');
            $table->string('locale', 2)->default('tr');
            $table->string('ip', 45)->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['contact_messages', 'faqs', 'testimonials', 'pricing_plans', 'educations', 'experiences', 'repositories', 'services', 'projects'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
