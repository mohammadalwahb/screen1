<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ServiceSearchRequest;
use App\Http\Requests\Api\UpdatedSinceRequest;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        $services = Service::query()
            ->select([
                'id',
                'name',
                'description',
                'building_id',
                'floor',
                'room',
                'keywords',
                'is_active',
                'updated_at',
                'deleted_at',
            ])
            ->with(['building:id,name'])
            ->orderBy('id')
            ->get();

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
            ->select(['id', 'name', 'building_id', 'floor', 'room'])
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
