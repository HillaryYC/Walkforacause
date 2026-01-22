<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Causes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('status'))
                        <div class="mb-4 rounded-md bg-green-50 px-4 py-3 text-sm text-green-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-4 flex justify-end">
                        <x-primary-button type="button" onclick="window.location='{{ route('admin.causes.create') }}'">
                            Add cause
                        </x-primary-button>
                    </div>

                    @if ($causes->isEmpty())
                        <div class="rounded-lg border border-dashed border-gray-300 p-6 text-center">
                            <p class="text-sm text-gray-600">No causes created yet.</p>
                            <x-primary-button type="button" onclick="window.location='{{ route('admin.causes.create') }}'" class="mt-4">
                                Add cause
                            </x-primary-button>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Cause</th>
                                        <th class="px-4 py-2 text-right font-semibold text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($causes as $cause)
                                        <tr>
                                            <td class="px-4 py-2 text-gray-900">{{ $cause->name }}</td>
                                            <td class="px-4 py-2 text-right">
                                                <div class="inline-flex items-center gap-2">
                                                    <a href="{{ route('admin.causes.edit', $cause) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">Edit</a>
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
            </div>
        </div>
    </div>
</x-app-layout>
