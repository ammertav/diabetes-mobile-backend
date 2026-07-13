<?php

use App\Models\User;
use App\Models\AdminProfile;
use App\Models\CmsContent;
use App\Enums\UserType;
use App\Enums\Gender;
use App\Enums\CmsContentType;
use App\Enums\CmsDayContext;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::create([
        'email' => 'admin@example.com',
        'type' => UserType::ADMIN,
    ]);

    AdminProfile::create([
        'user_id' => $this->admin->id,
        'name' => 'Admin Test',
        'age' => 30,
        'gender' => Gender::MALE,
    ]);

    // Create seed content
    CmsContent::create([
        'content_type' => CmsContentType::Education,
        'day_context' => CmsDayContext::Monday,
        'title' => 'Panduan Nutrisi',
        'body' => 'Isi panduan nutrisi harian.',
        'is_published' => true,
    ]);

    CmsContent::create([
        'content_type' => CmsContentType::SafetyGuide,
        'day_context' => CmsDayContext::Thursday,
        'title' => 'Langkah Darurat',
        'body' => 'Isi langkah darurat hipoglikemia.',
        'is_published' => false,
    ]);
});

test('guest cannot access cms index', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->get('/cms');
    $response->assertRedirect('/login');
});

test('admin can see all cms contents', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/cms');

    $response->assertStatus(200);
    $response->assertSee('Panduan Nutrisi');
    $response->assertSee('Langkah Darurat');
});

test('admin can filter cms contents by search query', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/cms?search=Nutrisi');

    $response->assertStatus(200);
    $response->assertSee('Panduan Nutrisi');
    $response->assertDontSee('Langkah Darurat');
});

test('admin can filter cms contents by type', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/cms?type=safety_guide');

    $response->assertStatus(200);
    $response->assertDontSee('Panduan Nutrisi');
    $response->assertSee('Langkah Darurat');
});

test('admin can filter cms contents by day context', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/cms?day_context=monday');

    $response->assertStatus(200);
    $response->assertSee('Panduan Nutrisi');
    $response->assertDontSee('Langkah Darurat');
});

test('admin can store new cms content', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->post('/cms', [
        'title' => 'Test Motivasi Baru',
        'type' => 'motivation',
        'day_context' => 'general',
        'body' => 'Isi motivasi test baru.',
        'is_published' => '1',
    ]);

    $response->assertRedirect('/cms');
    $response->assertSessionHas('success', 'Konten berhasil dibuat.');

    $this->assertDatabaseHas('cms_contents', [
        'title' => 'Test Motivasi Baru',
        'content_type' => 'motivation',
        'day_context' => 'general',
        'is_published' => true,
    ]);
});

test('admin can update existing cms content', function () {
    $content = CmsContent::first();

    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->put("/cms/{$content->id}", [
        'title' => 'Judul Baru Terupdate',
        'type' => 'nutrition',
        'day_context' => 'general',
        'body' => 'Isi konten terupdate.',
        'is_published' => '0',
    ]);

    $response->assertRedirect('/cms');
    $response->assertSessionHas('success', 'Konten berhasil diperbarui.');

    $this->assertDatabaseHas('cms_contents', [
        'id' => $content->id,
        'title' => 'Judul Baru Terupdate',
        'content_type' => 'nutrition',
        'is_published' => false,
    ]);
});

test('admin can delete cms content', function () {
    $content = CmsContent::first();

    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->delete("/cms/{$content->id}");

    $response->assertRedirect('/cms');
    $response->assertSessionHas('success', 'Konten berhasil dihapus.');

    $this->assertDatabaseMissing('cms_contents', [
        'id' => $content->id,
    ]);
});
