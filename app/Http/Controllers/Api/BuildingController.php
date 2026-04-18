<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdatedSinceRequest;
use App\Models\Building;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids' => ['sometimes', 'nullable', 'string', 'max:500'],
        ]);

        $query = Building::query()
            ->select(['id', 'name', 'description', 'map_image', 'updated_at', 'deleted_at'])
            ->orderBy('id');

        if (! empty($validated['ids'] ?? null)) {
            $ids = collect(explode(',', $validated['ids']))
                ->map(fn (string $value): int => (int) trim($value))
                ->filter(fn (int $id): bool => $id > 0)
                ->unique()
                ->values()
                ->all();
            if ($ids !== []) {
                $query->whereIn('id', $ids);
            }
        }

        $buildings = $query->get();

        return response()->json([
            'data' => $buildings,
            'synced_at' => now()->toISOString(),
        ]);
    }

    public function show(Building $building): JsonResponse
    {
        return response()->json([
            'data' => $building->only(['id', 'name', 'description', 'map_image', 'updated_at', 'deleted_at']),
        ]);
    }

    public function updated(UpdatedSinceRequest $request): JsonResponse
    {
        $after = $request->date('after');

        $buildings = Building::withTrashed()
            ->select(['id', 'name', 'description', 'map_image', 'updated_at', 'deleted_at'])
            ->where('updated_at', '>', $after)
            ->orWhere(fn ($query) => $query->whereNotNull('deleted_at')->where('deleted_at', '>', $after))
            ->orderBy('updated_at')
            ->get();

        return response()->json([
            'data' => $buildings,
            'synced_at' => now()->toISOString(),
        ]);
    }
}
