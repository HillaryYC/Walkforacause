<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Cause') }}
            </h2>
            <a href="{{ route('admin.causes.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">
                Back to causes
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.causes.update', $cause) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        @include('admin.causes._form', ['cause' => $cause])

                        <div class="flex items-center justify-end gap-3">
                            <x-secondary-button type="button" onclick="window.location='{{ route('admin.causes.index') }}'">
                                Cancel
                            </x-secondary-button>
                            <x-primary-button>
                                Update cause
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
