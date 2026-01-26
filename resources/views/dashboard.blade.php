<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold">Log your walk</h3>
                        <p class="mt-2 text-sm text-gray-600">
                            Choose a cause and submit your distance anytime. You can log as often as you like with any positive distance.
                        </p>
                        <a href="{{ route('causes.index') }}" class="mt-4 inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-500">
                            Browse causes
                        </a>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold">Track leaderboards</h3>
                        <p class="mt-2 text-sm text-gray-600">
                            Each cause has a leaderboard so you can see how far everyone has walked.
                        </p>
                        <a href="{{ route('leaderboards.index') }}" class="mt-4 inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-500">
                            View leaderboards
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
