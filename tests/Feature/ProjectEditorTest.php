<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectEditorTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_save_editor_changes(): void
    {
        $user = User::factory()->create([
            'account_status' => 'approved',
        ]);

        $project = Project::query()->create([
            'user_id' => $user->id,
            'name' => 'Editor Test',
            'niche' => 'digital-product',
            'business_type' => 'digital product',
            'raw_input' => ['nama_project' => 'Editor Test'],
            'generated_data' => [
                'meta' => ['project_name' => 'Editor Test'],
                'theme' => ['name' => 'test-theme'],
                'hero' => ['headline' => 'Headline Lama'],
                'sections' => [],
            ],
            'edited_data' => [
                'meta' => ['project_name' => 'Editor Test'],
                'theme' => ['name' => 'test-theme'],
                'hero' => ['headline' => 'Headline Lama'],
                'sections' => [],
            ],
        ]);

        $payload = [
            'name' => 'Editor Test Updated',
            'edited_data' => json_encode([
                'meta' => ['project_name' => 'Editor Test Updated'],
                'theme' => ['name' => 'test-theme'],
                'hero' => ['headline' => 'Headline Baru'],
                'sections' => [],
            ], JSON_THROW_ON_ERROR),
        ];

        $this->actingAs($user)
            ->put(route('projects.update', $project), $payload)
            ->assertRedirect();

        $project->refresh();

        $this->assertSame('Editor Test Updated', $project->name);
        $this->assertSame('Headline Baru', data_get($project->edited_data, 'hero.headline'));
        $this->assertSame('Editor Test Updated', data_get($project->edited_data, 'meta.project_name'));
    }
}
