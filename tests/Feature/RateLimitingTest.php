<?php

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it limits requests for guests', function () {
    // 1. Ensure the limiter starts fresh
    RateLimiter::clear('global:'.request()->ip());

    // 2. Perform the request
    $response = $this->get('/');

    // 3. Assert headers (Laravel's throttle middleware adds these automatically)
    $response->assertHeader('X-RateLimit-Limit', 60);
    $response->assertHeader('X-RateLimit-Remaining', 59);
});

test('it limits requests for authenticated users', function () {
    $user = User::factory()->create();
    
    // 1. Act as the user
    $this->actingAs($user);
    
    // 2. Perform the request
    $response = $this->get('/');
    
    // 3. Assert headers
    $response->assertHeader('X-RateLimit-Limit', 60);
});

test('it has a stricter limit for finance actions', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Hit a finance route
    $response = $this->get('/transactions');
    
    // Should show the stricter 40 limit instead of the global 60
    $response->assertHeader('X-RateLimit-Limit', 40);
});

test('it returns 429 when limit is exceeded', function () {
    // 1. Hit the endpoint exactly up to the limit (60 times)
    for ($i = 0; $i < 60; $i++) {
        $this->get('/');
    }

    // 2. The 61st request pushes it over the edge
    $response = $this->get('/');
    
    // 3. Assert the door is closed
    $response->assertStatus(429);
});