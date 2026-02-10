<x-app-layout :hide-header="true">
    <div class="mx-auto grid max-w-5xl gap-6">
        <section class="rounded-2xl border border-[var(--app-border)] bg-white p-5 shadow-sm sm:p-7">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </section>

        <section class="rounded-2xl border border-[var(--app-border)] bg-white p-5 shadow-sm sm:p-7">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </section>

        <section class="rounded-2xl border border-[var(--app-border)] bg-white p-5 shadow-sm sm:p-7">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </section>
    </div>
</x-app-layout>
