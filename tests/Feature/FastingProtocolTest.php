<?php

use App\Models\User;
use App\Models\AdminProfile;
use App\Models\FastingProtocol;
use App\Enums\UserType;
use App\Enums\Gender;
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

    // Create seed protocol
    $this->protocol = FastingProtocol::create([
        'name' => 'Puasa Senin-Kamis',
        'type' => 'sunnah',
        'duration_hours' => 13,
        'description' => 'Puasa sunnah Senin dan Kamis',
    ]);
    
    $this->protocol->days()->create(['day' => 1]);
    $this->protocol->days()->create(['day' => 4]);
});

test('guest cannot access fasting protocols index', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->get('/fasting-protocols');
    $response->assertRedirect('/login');
});

test('admin can see fasting protocols list', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/fasting-protocols');

    $response->assertStatus(200);
    $response->assertSee('Puasa Senin-Kamis');
});

test('admin can access create page', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->get('/fasting-protocols/create');

    $response->assertStatus(200);
    $response->assertSee('Create New Protocol');
});

test('admin can store new fasting protocol with days', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->post('/fasting-protocols', [
        'name' => 'Intermittent 16:8 New',
        'type' => 'intermittent',
        'duration_hours' => 16,
        'description' => 'Isi deskripsi intermittent baru.',
        'days' => [1, 2, 3, 4, 5, 6, 7],
    ]);

    $response->assertRedirect('/fasting-protocols');
    $response->assertSessionHas('success', 'Protokol puasa berhasil dibuat.');

    $this->assertDatabaseHas('fasting_protocols', [
        'name' => 'Intermittent 16:8 New',
        'type' => 'intermittent',
        'duration_hours' => 16,
    ]);

    $protocol = FastingProtocol::where('name', 'Intermittent 16:8 New')->first();
    $this->assertCount(7, $protocol->days);
});

test('admin can delete fasting protocol', function () {
    /** @var \Illuminate\Foundation\Testing\TestCase $this */
    $response = $this->actingAs($this->admin)->delete("/fasting-protocols/{$this->protocol->id}");

    $response->assertRedirect('/fasting-protocols');
    $response->assertSessionHas('success', 'Protokol puasa berhasil dihapus.');

    $this->assertDatabaseMissing('fasting_protocols', [
        'id' => $this->protocol->id,
    ]);
});
