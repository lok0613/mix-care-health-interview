<?php

use App\Models\{Task, User};
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows successful task creation and validation error', function () {
    $user = User::factory()->create();

    // ✅ Successful creation
    $response = $this->actingAs($user, 'sanctum')->postJson('/api/tasks', [
        'title' => 'My First Task',
        'description' => 'Test description',
        'status' => 'pending',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.title', 'My First Task');

    // ❌ Validation error (missing title)
    $badResponse = $this->actingAs($user, 'sanctum')->postJson('/api/tasks', [
        'description' => 'No title here',
    ]);

    $badResponse->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

it('prevents accessing another user’s task', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $task = Task::factory()->for($userA)->create();

    // User B tries to access User A's task
    $response = $this->actingAs($userB, 'sanctum')->getJson("/api/tasks/{$task->id}");

    $response->assertStatus(403);

    // User A tries to access it's own task
    $response = $this->actingAs($userA, 'sanctum')->getJson("/api/tasks/{$task->id}");

    $response->assertStatus(200);
});

it('lists tasks filtered by status', function () {
    $user = User::factory()->create();

    Task::factory()->for($user)->create(['status' => 'pending']);
    Task::factory()->for($user)->create(['status' => 'completed']);

    // Filter by pending
    $response = $this->actingAs($user, 'sanctum')->getJson('/api/tasks?status=pending');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.status', 'pending');
});
