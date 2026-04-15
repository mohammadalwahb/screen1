@extends('layouts.admin')

@section('content')
    <h1>Buildings</h1>
    <p><a href="{{ route('admin.buildings.create') }}">+ Create Building</a></p>

    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Map</th>
            <th>Updated</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($buildings as $building)
            <tr>
                <td>{{ $building->name }}</td>
                <td>{{ $building->description }}</td>
                <td>{{ $building->map_image ?? '-' }}</td>
                <td>{{ $building->updated_at }}</td>
                <td>
                    <div class="actions">
                        <a href="{{ route('admin.buildings.edit', $building) }}">Edit</a>
                        <form action="{{ route('admin.buildings.destroy', $building) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="danger" onclick="return confirm('Delete this building?')">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No buildings found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $buildings->links() }}
@endsection
