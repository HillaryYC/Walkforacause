<x-app-layout :hide-header="true">
    <div class="rounded-3xl border border-[var(--app-border)] bg-white p-4 shadow-sm sm:p-6 lg:p-8">
        <div class="mb-6 border-b border-blue-100 pb-5">
            <h1 class="text-2xl font-semibold text-slate-900">Manage Users</h1>
            <p class="mt-1 text-sm text-slate-500">Assign roles to control what each user can access.</p>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-xl bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-blue-100 text-xs font-semibold uppercase tracking-wide text-slate-400">
                        <th class="pb-3 pr-4 font-semibold">Name</th>
                        <th class="pb-3 pr-4 font-semibold">Email</th>
                        <th class="pb-3 pr-4 font-semibold">Role</th>
                        <th class="pb-3 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-50">
                    @foreach ($users as $user)
                        @php($initial = strtoupper(substr($user->name, 0, 1)) ?: 'U')
                        <tr class="transition hover:bg-blue-50/50">
                            <td class="py-3.5 pr-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-500 text-xs font-semibold text-white">
                                        {{ $initial }}
                                    </div>
                                    <span class="truncate font-semibold text-slate-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 pr-4 text-slate-500">{{ $user->email }}</td>
                            <td class="py-3.5 pr-4">
                                @if ($user->role === 'super_admin')
                                    <span class="inline-flex rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-semibold text-purple-700">Super Admin</span>
                                @elseif ($user->role === 'admin')
                                    <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-700">Admin</span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">User</span>
                                @endif
                            </td>
                            <td class="py-3.5 text-right">
                                @if ($user->id !== auth()->id())
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-semibold text-blue-500 hover:text-blue-700">Edit Role</a>
                                @else
                                    <span class="text-xs text-slate-400">You</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
