<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Causes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Available causes</h3>
                    </div>

                    @if ($causes->isEmpty())
                        <p class="mt-4 text-sm text-gray-600">No causes are available yet. Please check back later.</p>
                    @else
                        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($causes as $cause)
                                <a href="{{ route('causes.show', $cause) }}" class="block rounded-lg border border-gray-200 p-4 transition hover:border-indigo-400 hover:shadow-sm">
                                    <h4 class="text-base font-semibold text-gray-900">{{ $cause->name }}</h4>
                                    <p class="mt-2 text-sm text-gray-600">
                                        {{ $cause->description ?: 'No description yet.' }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

