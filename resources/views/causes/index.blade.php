<x-app-layout :hide-header="true">
    <div x-data="{ editCauseId: null, editCauseName: '', editCauseDescription: '' }">
        <div class="rounded-3xl bg-white/80 p-6 shadow-xl shadow-slate-200/70 backdrop-blur sm:p-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Causes</p>
                <h1 class="mt-2 text-2xl font-semibold text-slate-900">Available Causes</h1>
            </div>
            @if (auth()->user()?->is_admin)
                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-full bg-blue-500 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white shadow"
                    x-data
                    x-on:click="$dispatch('open-modal', 'add-cause')"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                    </svg>
                    Add Cause
                </button>
            @endif
        </div>

        @if ($causes->isEmpty())
            <div class="mt-6 rounded-2xl bg-white px-4 py-3 text-sm text-slate-600 shadow-sm">
                No causes are available yet. Please check back later.
            </div>
        @else
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($causes as $cause)
                    <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-sm transition hover:border-slate-200 hover:shadow-md">
                        <div class="flex items-start justify-between gap-3">
                            <a href="{{ route('causes.show', $cause) }}" class="flex-1">
                                <h4 class="text-base font-semibold text-slate-900">{{ $cause->name }}</h4>
                                <p class="mt-2 text-sm text-slate-600">
                                    {{ $cause->description ?: 'No description yet.' }}
                                </p>
                            </a>
                            @if (auth()->user()?->is_admin)
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-500 transition hover:bg-blue-200"
                                        title="Edit"
                                        x-on:click="
                                            editCauseId = {{ $cause->id }};
                                            editCauseName = @js($cause->name);
                                            editCauseDescription = @js($cause->description ?? '');
                                            $dispatch('open-modal', 'edit-cause')
                                        "
                                    >
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M12 20h9"></path>
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                        </svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.causes.destroy', $cause) }}" onsubmit="return confirm('Delete this cause?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-500 transition hover:bg-blue-200" title="Delete">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M3 6h18"></path>
                                                <path d="M8 6V4h8v2"></path>
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                                <path d="M10 11v6"></path>
                                                <path d="M14 11v6"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

        @if (auth()->user()?->is_admin)
            <x-modal name="add-cause" maxWidth="lg">
            <div class="px-6 py-5">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Add Cause</h2>
                        <p class="text-sm text-slate-600">Create a new cause for users to support.</p>
                    </div>
                    <button class="text-slate-400" x-on:click="$dispatch('close-modal', 'add-cause')">Close</button>
                </div>

                <form method="POST" action="{{ route('admin.causes.store') }}" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Name</label>
                        <input
                            type="text"
                            name="name"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700"
                            required
                        />
                        @error('name')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Description</label>
                        <textarea
                            name="description"
                            rows="3"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700"
                        ></textarea>
                        @error('description')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-blue-500 py-2.5 text-sm font-semibold text-white shadow">Save Cause</button>
                </form>
            </div>
        </x-modal>

            <x-modal name="edit-cause" maxWidth="lg">
            <div class="px-6 py-5">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Edit Cause</h2>
                        <p class="text-sm text-slate-600">Update the cause details.</p>
                    </div>
                    <button class="text-slate-400" x-on:click="$dispatch('close-modal', 'edit-cause')">Close</button>
                </div>

                <form
                    method="POST"
                    class="mt-5 space-y-4"
                    x-bind:action="editCauseId ? '{{ url('/admin/causes') }}/' + editCauseId : '#'"
                >
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Name</label>
                        <input
                            type="text"
                            name="name"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700"
                            x-model="editCauseName"
                            required
                        />
                        @error('name')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Description</label>
                        <textarea
                            name="description"
                            rows="3"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700"
                            x-model="editCauseDescription"
                        ></textarea>
                        @error('description')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-blue-500 py-2.5 text-sm font-semibold text-white shadow">Save Changes</button>
                </form>
            </div>
            </x-modal>
        @endif
    </div>
</x-app-layout>
