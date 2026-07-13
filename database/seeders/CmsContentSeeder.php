<?php

namespace Database\Seeders;

use App\Models\CmsContent;
use App\Enums\CmsContentType;
use App\Enums\CmsDayContext;
use Illuminate\Database\Seeder;

class CmsContentSeeder extends Seeder
{
    public function run(): void
    {
        CmsContent::create([
            'content_type' => CmsContentType::Education,
            'day_context' => CmsDayContext::Monday,
            'title' => 'Panduan Gizi untuk Penderita Diabetes Tipe 2',
            'body' => 'Nutrisi yang tepat adalah kunci utama dalam pengelolaan kadar gula darah. Dalam modul ini, kita akan membahas porsi piring makan yang seimbang, memilih karbohidrat kompleks, dan pentingnya serat dalam diet harian Anda.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        CmsContent::create([
            'content_type' => CmsContentType::Motivation,
            'day_context' => CmsDayContext::General,
            'title' => 'Setiap Langkah Kecil Berarti untuk Kesehatan Anda',
            'body' => 'Kesehatan bukanlah tujuan, melainkan sebuah perjalanan...',
            'is_published' => true,
            'published_at' => now(),
        ]);

        CmsContent::create([
            'content_type' => CmsContentType::Nutrition,
            'day_context' => CmsDayContext::General,
            'title' => 'Pentingnya Hidrasi',
            'body' => 'Mengapa air putih lebih baik daripada minuman manis?',
            'is_published' => true,
            'published_at' => now(),
        ]);

        CmsContent::create([
            'content_type' => CmsContentType::SafetyGuide,
            'day_context' => CmsDayContext::Thursday,
            'title' => 'Pertolongan Pertama Hipoglikemia',
            'body' => 'Langkah darurat saat gula darah turun drastis di bawah normal.',
            'is_published' => false,
            'published_at' => null,
        ]);

        CmsContent::create([
            'content_type' => CmsContentType::Reflection,
            'day_context' => CmsDayContext::Monday,
            'title' => 'Meditasi & Ketenangan',
            'body' => 'Sesi refleksi diri untuk menjaga kesehatan mental pasien diabetes.',
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
