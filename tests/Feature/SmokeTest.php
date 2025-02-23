<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('Access login page', function () {
    get('/login')
        ->assertStatus(200);
});

it('Access dashboard page', function () {
    actingAs($this->user)
        ->get('/')
        ->assertStatus(200);
});

it('Access invoices list page', function () {
    actingAs($this->user)
        ->get('/invoices')
        ->assertStatus(200);
});

it('Access reports list page', function () {
    actingAs($this->user)
        ->get('/reports')
        ->assertStatus(200);
});

it('Access tasks list page', function () {
    actingAs($this->user)
        ->get('/tasks')
        ->assertStatus(200);
});

it('Access task hours list page', function () {
    actingAs($this->user)
        ->get('/task-hours')
        ->assertStatus(200);
});

it('Access contracts list page', function () {
    actingAs($this->user)
        ->get('/contracts')
        ->assertStatus(200);
});

it('Access suppliers list page', function () {
    actingAs($this->user)
        ->get('/suppliers')
        ->assertStatus(200);
});

it('Access customers list page', function () {
    actingAs($this->user)
        ->get('/customers')
        ->assertStatus(200);
});

it('Access addresses list page', function () {
    actingAs($this->user)
        ->get('/addresses')
        ->assertStatus(200);
});

it('Access bank-accounts list page', function () {
    actingAs($this->user)
        ->get('/bank-accounts')
        ->assertStatus(200);
});
