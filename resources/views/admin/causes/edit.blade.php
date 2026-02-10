<x-app-layout :hide-header="true">
    <div class="mx-auto max-w-3xl rounded-2xl border border-[var(--app-border)] bg-white p-5 shadow-sm sm:p-7">
        <div class="mb-6 flex flex-col gap-2 border-b border-slate-100 pb-5 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-semibold text-slate-900">{{ __('Edit Cause') }}</h1>
            <a href="{{ route('admin.causes.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">
                Back to causes
            </a>
        </div>

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
</x-app-layout>
