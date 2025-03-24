<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('admin login screen can be rendered', function () {
    $response = $this->get('admin/login');

    $response->assertStatus(200);
});

test('user can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('user can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('admin/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('can logout', function () {
    $user = User::factory()->create()->assignRole('admin');

    $response = $this->actingAs($user)->post('admin/logout');

    $this->assertGuest();
    $response->assertRedirect('/admin');
});
