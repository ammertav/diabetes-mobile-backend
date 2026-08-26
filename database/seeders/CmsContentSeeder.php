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
        CmsContent::updateOrCreate(
            ['title' => 'Panduan Gizi untuk Penderita Diabetes Tipe 2'],
            [
                'content_type' => CmsContentType::Education,
                'day_context' => CmsDayContext::Monday,
                'body' => 'Nutrisi yang tepat adalah kunci utama dalam pengelolaan kadar gula darah. Dalam modul ini, kita akan membahas porsi piring makan yang seimbang, memilih karbohidrat kompleks, dan pentingnya serat dalam diet harian Anda.',
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        CmsContent::updateOrCreate(
            ['title' => 'Setiap Langkah Kecil Berarti untuk Kesehatan Anda'],
            [
                'content_type' => CmsContentType::Motivation,
                'day_context' => CmsDayContext::General,
                'body' => 'Kesehatan bukanlah tujuan, melainkan sebuah perjalanan...',
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        CmsContent::updateOrCreate(
            ['title' => 'Pentingnya Hidrasi'],
            [
                'content_type' => CmsContentType::Nutrition,
                'day_context' => CmsDayContext::General,
                'body' => 'Mengapa air putih lebih baik daripada minuman manis?',
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        CmsContent::updateOrCreate(
            ['title' => 'Pertolongan Pertama Hipoglikemia'],
            [
                'content_type' => CmsContentType::SafetyGuide,
                'day_context' => CmsDayContext::Thursday,
                'body' => 'Langkah darurat saat gula darah turun drastis di bawah normal.',
                'is_published' => false,
                'published_at' => null,
            ]
        );

        CmsContent::updateOrCreate(
            ['title' => 'Meditasi & Ketenangan'],
            [
                'content_type' => CmsContentType::Reflection,
                'day_context' => CmsDayContext::Monday,
                'body' => 'Sesi refleksi diri untuk menjaga kesehatan mental pasien diabetes.',
                'is_published' => true,
                'published_at' => now(),
            ]
        );
    }
}
