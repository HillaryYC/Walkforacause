<x-app-layout :hide-header="true">
    <div class="mx-auto max-w-6xl rounded-2xl border border-[var(--app-border)] bg-white p-5 shadow-sm sm:p-7">
        <div class="mb-6 flex flex-col gap-3 border-b border-slate-100 pb-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.22em] text-slate-400">Admin</p>
                <h1 class="mt-2 text-2xl font-semibold text-slate-900">Manage Causes</h1>
            </div>
            <x-primary-button type="button" onclick="window.location='{{ route('admin.causes.create') }}'">
                Add cause
            </x-primary-button>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-md bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($causes->isEmpty())
            <div class="rounded-xl border border-dashed border-slate-300 p-6 text-center">
                <p class="text-sm text-slate-600">No causes created yet.</p>
                <x-primary-button type="button" onclick="window.location='{{ route('admin.causes.create') }}'" class="mt-4">
                    Add cause
                </x-primary-button>
            </div>
        @else
            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Cause</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($causes as $cause)
                            <tr>
                                <td class="px-4 py-3 text-slate-900">{{ $cause->name }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex items-center gap-3">
                                        <a href="{{ route('admin.causes.edit', $cause) }}" class="text-sm font-semibold text-slate-700 hover:text-slate-900">Edit</a>
                                        <form method="POST" action="{{ route('admin.causes.destroy', $cause) }}" onsubmit="return confirm('Delete this cause?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-500">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
