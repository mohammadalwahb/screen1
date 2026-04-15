<?php

namespace Tests\Feature\Api;

use App\Models\Building;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_buildings_index_endpoint_returns_data(): void
    {
        Building::factory()->count(2)->create();

        $response = $this->getJson('/api/buildings');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'name', 'description', 'map_image', 'updated_at', 'deleted_at']],
                'synced_at',
            ]);
    }

    public function test_services_search_endpoint_filters_by_name_and_keyword(): void
    {
        $building = Building::factory()->create(['name' => 'Main Campus']);

        Service::factory()->create([
            'name' => 'Admissions Office',
            'building_id' => $building->id,
            'keywords' => ['admission', 'student'],
            'floor' => '2',
            'room' => '210',
            'is_active' => true,
        ]);

        Service::factory()->create([
            'name' => 'Finance Helpdesk',
            'building_id' => $building->id,
            'keywords' => ['billing'],
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/services/search?q=admission');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['name' => 'Admissions Office', 'building' => 'Main Campus']);
    }

    public function test_updated_endpoints_include_soft_deleted_rows_after_timestamp(): void
    {
        $building = Building::factory()->create();
        $service = Service::factory()->create(['building_id' => $building->id]);

        $after = Carbon::now()->subMinute()->toISOString();
        $service->delete();

        $response = $this->getJson('/api/services/updated?after='.$after);

        $response
            ->assertOk()
            ->assertJsonFragment(['id' => $service->id])
            ->assertJsonFragment(['deleted_at' => $service->fresh()->deleted_at?->toISOString()]);
    }
}
