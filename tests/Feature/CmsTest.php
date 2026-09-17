<?php

use App\Enums\CmsContentType;
use App\Enums\CmsDayContext;
use App\Enums\CmsMediaType;
use App\Enums\Gender;
use App\Enums\UserType;
use App\Models\AdminProfile;
use App\Models\CmsContent;
use App\Models\User;
use App\Utilities\JwtUtility;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(RefreshDatabase::class);

beforeEach(function () {
    /** @var TestCase $this */
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
    /** @var TestCase $this */
    $response = $this->get('/cms');
    $response->assertRedirect('/login');
});

test('admin can see all cms contents', function () {
    /** @var TestCase $this */
    $response = $this->actingAs($this->admin)->get('/cms');

    $response->assertStatus(200);
    $response->assertSee('Panduan Nutrisi');
    $response->assertSee('Langkah Darurat');
});

test('admin can filter cms contents by search query', function () {
    /** @var TestCase $this */
    $response = $this->actingAs($this->admin)->get('/cms?search=Nutrisi');

    $response->assertStatus(200);
    $response->assertSee('Panduan Nutrisi');
    $response->assertDontSee('Langkah Darurat');
});

test('admin can filter cms contents by type', function () {
    /** @var TestCase $this */
    $response = $this->actingAs($this->admin)->get('/cms?type=safety_guide');

    $response->assertStatus(200);
    $response->assertDontSee('Panduan Nutrisi');
    $response->assertSee('Langkah Darurat');
});

test('admin can filter cms contents by day context', function () {
    /** @var TestCase $this */
    $response = $this->actingAs($this->admin)->get('/cms?day_context=monday');

    $response->assertStatus(200);
    $response->assertSee('Panduan Nutrisi');
    $response->assertDontSee('Langkah Darurat');
});

test('admin can store new cms content', function () {
    /** @var TestCase $this */
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
    $content = CmsContent::query()->first();

    /** @var TestCase $this */
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
    $content = CmsContent::query()->first();

    /** @var TestCase $this */
    $response = $this->actingAs($this->admin)->delete("/cms/{$content->id}");

    $response->assertRedirect('/cms');
    $response->assertSessionHas('success', 'Konten berhasil dihapus.');

    $this->assertDatabaseMissing('cms_contents', [
        'id' => $content->id,
    ]);
});

test('admin can upload image to cms content', function () {
    Storage::fake('cms');
    $image = UploadedFile::fake()->image('banner.jpg', 600, 400);

    /** @var TestCase $this */
    $response = $this->actingAs($this->admin)->post('/cms', [
        'title' => 'Edukasi Bergambar',
        'type' => 'education',
        'body' => 'Isi edukasi bergambar.',
        'is_published' => '1',
        'media_type' => 'image',
        'image_file' => $image,
    ]);

    $response->assertRedirect('/cms');
    $content = CmsContent::query()->where('title', 'Edukasi Bergambar')->first();
    expect($content)->not->toBeNull()
        ->and($content->media_type)->toBe(CmsMediaType::Image)
        ->and($content->media_url)->not->toBeNull();

    /** @var FilesystemAdapter $disk */
    $disk = Storage::disk('cms');
    $disk->assertExists($content->media_url);
});

test('admin can upload video under 50mb with custom thumbnail', function () {
    Storage::fake('cms');
    $video = UploadedFile::fake()->create('workout.mp4', 35000, 'video/mp4');
    $thumb = UploadedFile::fake()->image('thumb.jpg', 400, 300);

    /** @var TestCase $this */
    $response = $this->actingAs($this->admin)->post('/cms', [
        'title' => 'Video Workout Diabetes',
        'type' => 'education',
        'body' => 'Ikuti gerakan senam ini.',
        'is_published' => '1',
        'media_type' => 'video',
        'video_file' => $video,
        'thumbnail_file' => $thumb,
    ]);

    $response->assertRedirect('/cms');
    $content = CmsContent::query()->where('title', 'Video Workout Diabetes')->first();
    expect($content)->not->toBeNull()
        ->and($content->media_type)->toBe(CmsMediaType::Video)
        ->and($content->media_url)->not->toBeNull()
        ->and($content->thumbnail_url)->not->toBeNull();

    /** @var FilesystemAdapter $disk */
    $disk = Storage::disk('cms');
    $disk->assertExists($content->media_url);
    $disk->assertExists($content->thumbnail_url);
});

test('admin cannot upload video exceeding 50mb', function () {
    Storage::fake('cms');
    $largeVideo = UploadedFile::fake()->create('heavy.mp4', 55000, 'video/mp4');

    /** @var TestCase $this */
    $response = $this->actingAs($this->admin)->post('/cms', [
        'title' => 'Video Terlalu Besar',
        'type' => 'education',
        'body' => 'Video berukuran besar.',
        'is_published' => '1',
        'media_type' => 'video',
        'video_file' => $largeVideo,
    ]);

    $response->assertSessionHasErrors(['video_file']);
});

test('admin can save youtube link with auto-extracted id and thumbnail', function () {
    /** @var TestCase $this */
    $response = $this->actingAs($this->admin)->post('/cms', [
        'title' => 'Video Edukasi YouTube',
        'type' => 'education',
        'body' => 'Video panduan dari YouTube.',
        'is_published' => '1',
        'media_type' => 'youtube',
        'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    ]);

    $response->assertRedirect('/cms');
    $content = CmsContent::query()->where('title', 'Video Edukasi YouTube')->first();
    expect($content)->not->toBeNull()
        ->and($content->media_type)->toBe(CmsMediaType::Youtube)
        ->and($content->youtube_id)->toBe('dQw4w9WgXcQ')
        ->and($content->thumbnail_url)->toBe('https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg');
});

test('admin can create content with media type none', function () {
    /** @var TestCase $this */
    $response = $this->actingAs($this->admin)->post('/cms', [
        'title' => 'Teks Motivasi Murni',
        'type' => 'motivation',
        'body' => 'Motivasi tanpa media.',
        'is_published' => '1',
        'media_type' => 'none',
    ]);

    $response->assertRedirect('/cms');
    $content = CmsContent::query()->where('title', 'Teks Motivasi Murni')->first();
    expect($content->media_type)->toBe(CmsMediaType::None)
        ->and($content->media_url)->toBeNull();
});

test('mobile api returns media payload with full url', function () {
    Storage::fake('cms');
    $image = UploadedFile::fake()->image('banner.jpg');
    $path = $image->store('cms/images', 'cms');

    CmsContent::create([
        'title' => 'Konten Dengan Media API',
        'content_type' => CmsContentType::Education,
        'body' => 'Konten untuk pengetesan mobile API.',
        'media_type' => CmsMediaType::Image,
        'media_url' => $path,
        'is_published' => true,
        'published_at' => now(),
    ]);

    $patient = User::create([
        'email' => 'patient@example.com',
        'type' => UserType::MOBILE,
    ]);

    $token = JwtUtility::generateAccessToken($patient);

    /** @var TestCase $this */
    $response = $this->withHeader('Authorization', 'Bearer '.$token)->getJson('/api/v1/content');
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'title',
                'body',
                'media' => [
                    'type',
                    'url',
                    'youtube_id',
                    'thumbnail_url',
                ],
            ],
        ],
    ]);
});
