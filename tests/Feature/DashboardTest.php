<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
        'role' => 'user',
    ]);
    $this->actingAs($user);

    $response = $this->get('/dashboard');
    $response->assertRedirect('/user/dashboard');
});