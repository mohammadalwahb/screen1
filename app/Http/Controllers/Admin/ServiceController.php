<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Models\Building;
use App\Models\Service;
use App\Support\Keywords;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::query()
            ->select(['id', 'name', 'building_id', 'floor', 'room', 'is_active', 'updated_at'])
            ->with(['building:id,name'])
            ->latest('updated_at')
            ->paginate(20);

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        $buildings = Building::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        return view('admin.services.create', compact('buildings'));
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $payload['keywords'] = Keywords::fromString($request->input('keywords'));

        Service::create($payload);

        return redirect()
            ->route('admin.services.index')
            ->with('status', 'Service created successfully.');
    }

    public function edit(Service $service): View
    {
        $buildings = Building::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        return view('admin.services.edit', [
            'service' => $service,
            'buildings' => $buildings,
        ]);
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $payload = $request->validated();
        $payload['keywords'] = Keywords::fromString($request->input('keywords'));

        $service->update($payload);

        return redirect()
            ->route('admin.services.index')
            ->with('status', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('status', 'Service deleted successfully.');
    }

    public function toggleStatus(Service $service): RedirectResponse
    {
        $service->update(['is_active' => ! $service->is_active]);

        return redirect()
            ->route('admin.services.index')
            ->with('status', 'Service status updated successfully.');
    }
}
