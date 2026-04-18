@extends('layouts.admin')

@section('content')
    <h1>Services</h1>
    <p><a href="{{ route('admin.services.create') }}">+ Create Service</a></p>

    <table>
        <thead>
        <tr>
            <th>Picture</th>
            <th>Name</th>
            <th>Building</th>
            <th>Floor</th>
            <th>Room</th>
            <th>Status</th>
            <th>Updated</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($services as $service)
            <tr>
                <td>
                    @if($service->picture && \Illuminate\Support\Facades\Storage::disk('public')->exists($service->picture))
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($service->picture) }}" alt="" style="max-height:48px;max-width:64px;border-radius:6px;object-fit:cover;">
                    @else
                        —
                    @endif
                </td>
                <td>{{ $service->name }}</td>
                <td>{{ $service->building?->name }}</td>
                <td>{{ $service->floor }}</td>
                <td>{{ $service->room }}</td>
                <td>{{ $service->is_active ? 'Active' : 'Inactive' }}</td>
                <td>{{ $service->updated_at }}</td>
                <td>
                    <div class="actions">
                        <a href="{{ route('admin.services.edit', $service) }}">Edit</a>
                        <form action="{{ route('admin.services.toggle-status', $service) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="secondary">
                                {{ $service->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="danger" onclick="return confirm('Delete this service?')">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8">No services found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $services->links() }}
@endsection
