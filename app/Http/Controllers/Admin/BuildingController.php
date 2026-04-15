<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBuildingRequest;
use App\Http\Requests\Admin\UpdateBuildingRequest;
use App\Models\Building;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BuildingController extends Controller
{
    public function index(): View
    {
        $buildings = Building::query()
            ->select(['id', 'name', 'description', 'map_image', 'updated_at'])
            ->latest('updated_at')
            ->paginate(20);

        return view('admin.buildings.index', compact('buildings'));
    }

    public function create(): View
    {
        return view('admin.buildings.create');
    }

    public function store(StoreBuildingRequest $request): RedirectResponse
    {
        $payload = $request->validated();

        if ($request->hasFile('map_image')) {
            $payload['map_image'] = $request->file('map_image')->store('building-maps', 'public');
        }

        Building::create($payload);

        return redirect()
            ->route('admin.buildings.index')
            ->with('status', 'Building created successfully.');
    }

    public function edit(Building $building): View
    {
        return view('admin.buildings.edit', compact('building'));
    }

    public function update(UpdateBuildingRequest $request, Building $building): RedirectResponse
    {
        $payload = $request->validated();

        if ($request->hasFile('map_image')) {
            if ($building->map_image) {
                Storage::disk('public')->delete($building->map_image);
            }

            $payload['map_image'] = $request->file('map_image')->store('building-maps', 'public');
        }

        $building->update($payload);

        return redirect()
            ->route('admin.buildings.index')
            ->with('status', 'Building updated successfully.');
    }

    public function destroy(Building $building): RedirectResponse
    {
        if ($building->services()->withTrashed()->exists()) {
            return redirect()
                ->route('admin.buildings.index')
                ->with('status', 'Building cannot be deleted while linked services exist.');
        }

        if ($building->map_image) {
            Storage::disk('public')->delete($building->map_image);
        }

        $building->delete();

        return redirect()
            ->route('admin.buildings.index')
            ->with('status', 'Building deleted successfully.');
    }
}
