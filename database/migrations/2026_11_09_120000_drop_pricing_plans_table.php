<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fiyatlandırma sitede kullanılmıyor: sayfa, panel kaynağı ve model kaldırıldı.
 *
 * Tabloyu oluşturan eski göç dosyasına dokunulmadı; yayındaki veritabanında
 * zaten çalışmış durumda, geriye dönük düzenlemek göç geçmişini bozar.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('pricing_plans');
    }

    public function down(): void
    {
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('badge')->nullable();
            $table->json('description');
            $table->json('price_label');
            $table->json('price');
            $table->json('items')->nullable();
            $table->json('cta');
            $table->string('contact_type')->default('web');
            $table->boolean('is_featured')->default(false);
            $table->unsignedSmallInteger('sort')->default(0);
            $table->timestamps();
        });
    }
};
