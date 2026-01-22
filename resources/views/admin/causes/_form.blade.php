@php($cause = $cause ?? null)

<div class="space-y-4">
    <div>
        <x-input-label for="name" value="Cause name" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', optional($cause)->name) }}" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" value="Description" />
        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', optional($cause)->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

</div>
