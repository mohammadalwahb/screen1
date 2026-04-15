@php($building = $building ?? null)

<label for="name">Name</label>
<input type="text" id="name" name="name" value="{{ old('name', $building?->name) }}" required>

<label for="description">Description</label>
<textarea id="description" name="description" rows="4">{{ old('description', $building?->description) }}</textarea>

<label for="map_image">Map Image</label>
<input type="file" id="map_image" name="map_image" accept=".jpg,.jpeg,.png,.webp">
@if($building?->map_image)
    <p>Current image path: {{ $building->map_image }}</p>
@endif

<button type="submit">Save</button>
