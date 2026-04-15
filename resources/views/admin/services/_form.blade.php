@php($service = $service ?? null)
@php($selectedKeywords = old('keywords', $service ? implode(', ', $service->keywords ?? []) : ''))

<label for="name">Name</label>
<input type="text" id="name" name="name" value="{{ old('name', $service?->name) }}" required>

<label for="description">Description</label>
<textarea id="description" name="description" rows="4">{{ old('description', $service?->description) }}</textarea>

<label for="building_id">Building</label>
<select id="building_id" name="building_id" required>
    <option value="">Select building</option>
    @foreach($buildings as $building)
        <option value="{{ $building->id }}" @selected((int) old('building_id', $service?->building_id) === $building->id)>
            {{ $building->name }}
        </option>
    @endforeach
</select>

<label for="floor">Floor</label>
<input type="text" id="floor" name="floor" value="{{ old('floor', $service?->floor) }}" required>

<label for="room">Room</label>
<input type="text" id="room" name="room" value="{{ old('room', $service?->room) }}" required>

<label for="keywords">Keywords (comma-separated)</label>
<textarea id="keywords" name="keywords" rows="3">{{ $selectedKeywords }}</textarea>

<label>
    <input type="checkbox" name="is_active" value="1" @checked((bool) old('is_active', $service?->is_active ?? true))>
    Active
</label>

<button type="submit">Save</button>
