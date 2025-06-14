<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_todo(): void
    {
        $payload = [
            'title' => 'Test Task',
            'assignee' => 'John Doe',
            'due_date' => now()->addDay()->toDateString(),
            'priority' => 'medium'
        ];

        $response = $this->postJson('/api/todos', $payload);

        $response->assertCreated()
            ->assertJsonFragment(['title' => 'Test Task']);

        $this->assertDatabaseHas('todos', ['title' => 'Test Task']);
    }

    #[Test]
    public function it_validates_due_date_is_not_in_past(): void
    {
        $payload = [
            'title' => 'Past Task',
            'due_date' => now()->subDay()->toDateString(),
            'priority' => 'low',
        ];

        $response = $this->postJson('/api/todos', $payload);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['due_date']);
    }

    #[Test]
    public function it_shows_a_todo(): void
    {
        $todo = Todo::factory()->create();

        $response = $this->getJson("/api/todos/{$todo->id}");
        $response->assertOk()
            ->assertJsonFragment(['id' => $todo->id]);
    }

    #[Test]
    public function it_updates_a_todo(): void
    {
        $todo = Todo::factory()->create();

        $response = $this->putJson("/api/todos/{$todo->id}", [
            'title' => 'Updated Title'
        ]);

        $response->assertOk()
            ->assertJson(['message' => 'Todo updated successfully']);

        $this->assertDatabaseHas('todos', ['id' => $todo->id, 'title' => 'Updated Title']);
    }

    #[Test]
    public function it_deletes_a_todo(): void
    {
        $todo = Todo::factory()->create();

        $response = $this->deleteJson("/api/todos/{$todo->id}");
        $response->assertOk()
            ->assertJson(['message' => 'Todo deleted successfully']);

        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }
}
