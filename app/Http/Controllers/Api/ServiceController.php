<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ServiceSearchRequest;
use App\Http\Requests\Api\UpdatedSinceRequest;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'building_ids' => ['sometimes', 'nullable', 'string', 'max:500'],
        ]);

        $query = Service::query()
            ->select([
                'id',
                'name',
                'description',
                'building_id',
                'floor',
                'room',
                'picture',
                'keywords',
                'is_active',
                'updated_at',
                'deleted_at',
            ])
            ->with(['building:id,name'])
            ->orderBy('id');

        if (! empty($validated['building_ids'] ?? null)) {
            $ids = collect(explode(',', $validated['building_ids']))
                ->map(fn (string $value): int => (int) trim($value))
                ->filter(fn (int $id): bool => $id > 0)
                ->unique()
                ->values()
                ->all();
            if ($ids !== []) {
                $query->whereIn('building_id', $ids);
            }
        }

        $services = $query->get();

        return response()->json([
            'data' => $services,
            'synced_at' => now()->toISOString(),
        ]);
    }

    public function show(Service $service): JsonResponse
    {
        $service->load('building:id,name');

        return response()->json([
            'data' => [
                ...$service->only([
                    'id',
                    'name',
                    'description',
                    'building_id',
                    'floor',
                    'room',
                    'picture',
                    'keywords',
                    'is_active',
                    'updated_at',
                    'deleted_at',
                ]),
                'building' => $service->building?->only(['id', 'name']),
            ],
        ]);
    }

    public function search(ServiceSearchRequest $request): JsonResponse
    {
        $query = trim($request->validated('q'));

        $services = Service::query()
            ->select(['id', 'name', 'building_id', 'floor', 'room', 'picture'])
            ->with(['building:id,name'])
            ->where('is_active', true)
            ->where(function ($serviceQuery) use ($query): void {
                $serviceQuery
                    ->where('name', 'like', "%{$query}%")
                    ->orWhereJsonContains('keywords', $query);
            })
            ->limit(50)
            ->get();

        return response()->json([
            'data' => $services->map(fn (Service $service): array => [
                'id' => $service->id,
                'name' => $service->name,
                'building' => $service->building?->name,
                'floor' => $service->floor,
                'room' => $service->room,
                'picture' => $service->picture,
            ]),
        ]);
    }

    public function updated(UpdatedSinceRequest $request): JsonResponse
    {
        $after = $request->date('after');

        $services = Service::withTrashed()
            ->select([
                'id',
                'name',
                'description',
                'building_id',
                'floor',
                'room',
                'picture',
                'keywords',
                'is_active',
                'updated_at',
                'deleted_at',
            ])
            ->with(['building:id,name'])
            ->where('updated_at', '>', $after)
            ->orWhere(fn ($query) => $query->whereNotNull('deleted_at')->where('deleted_at', '>', $after))
            ->orderBy('updated_at')
            ->get();

        return response()->json([
            'data' => $services,
            'synced_at' => now()->toISOString(),
        ]);
    }
}
