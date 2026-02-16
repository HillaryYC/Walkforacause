<x-app-layout :hide-header="true">
    <div class="mx-auto max-w-xl rounded-3xl border border-[var(--app-border)] bg-white p-4 shadow-sm sm:p-6 lg:p-8">
        <div class="mb-6 border-b border-blue-100 pb-5">
            <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">
                Back to users
            </a>
            <h1 class="mt-2 text-2xl font-semibold text-slate-900">Edit User Role</h1>
        </div>

        @if (session('error'))
            <div class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-6 rounded-xl bg-blue-50 p-4">
            <p class="text-sm font-semibold text-slate-900">{{ $user->name }}</p>
            <p class="text-sm text-slate-500">{{ $user->email }}</p>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-600">Role</label>
                <select
                    name="role"
                    class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700"
                >
                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
                @error('role')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit" class="flex-1 rounded-xl bg-blue-500 py-2.5 text-sm font-semibold text-white shadow hover:bg-blue-600">Save Role</button>
                <a href="{{ route('admin.users.index') }}" class="flex-1 rounded-xl border border-blue-200 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-blue-50">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
