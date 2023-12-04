<div>
    <div class="py-10">
        {{ $this->institutionInfolist }}
    </div>
    <x-filament-actions::modals />
    <div
        x-data="{ tab: 'users' }"
        class="block bg-gray-100 pb-10 rounded-t-xl "
    >
        <nav class="flex bg-white py-4 justify-center space-x-2 rounded-t-xl ring-1 ring-gray-950/5 shadow-sm">
            <a
                :class="{ 'border-gray-700 text-gray-400': tab === 'users' }"
                x-on:click.prevent="tab = 'users'"
                href="#"
                rel="noopener noreferrer"
                class="flex items-center flex-shrink-0 px-5 py-2 border-b-4
                dark:border-gray-700 dark:text-gray-400
                hover:border-gray-700 hover:text-gray-400"
            >
                Users
            </a>
        </nav>
        <div class="mb-10 rounded-b-xl ring-1 ring-gray-950/5 bg-white p-5 shadow-sm">
            <div x-show="tab === 'users'">
                <livewire:institution.user-institution :institution="$institution" />
            </div>
        </div>
    </div>
</div>
