<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the admin page', function () {
    $this->get('/admin/dashboard')->assertRedirect('/admin');
});

test('authenticated users can not visit the admin dashboard and are logged back out', function () {
    $this->actingAs($user = User::factory()->create()->assignRole(('user')));

    $this->get('/admin/dashboard')->assertRedirect('/admin/login');

    $this->assertGuest();
});

test('authenticated admin can not visit the admin dashboard', function () {
    $this->actingAs($user = User::factory()->create()->assignRole(('admin')));

    $this->get('/admin/dashboard')->assertStatus(200);
});
