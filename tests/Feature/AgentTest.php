<?php

namespace Tests\Feature;

use App\Models\DelayReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AgentTest extends TestCase
{
    use RefreshDatabase;

    public function test_assign_delay_report_not_found(): void
    {
        $agent = User::Factory()->create(['role' => 'agent']);
        
        $response = $this->post("api/agents/{$agent->id}/assign-delay-report");
        
        $response->assertStatus(404);
    }
    
    public function test_agent_has_delay_report(): void
    {
        $DelayReport = DelayReport::Factory()->create();
        
        $response = $this->post("api/agents/{$DelayReport->user_id}/assign-delay-report");
        
        $response->assertStatus(400);

    }
}
