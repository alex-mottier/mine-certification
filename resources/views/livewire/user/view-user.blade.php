<div>
    <div class="py-10">{{ $this->userInfolist }}</div>
    <x-filament-actions::modals />

    @if(
        $user->type === \App\Domain\User\UserType::INSTITUTION ||
        $user->type === \App\Domain\User\UserType::CERTIFIER ||
        $user->type === \App\Domain\User\UserType::OWNER
    )
    <div
        class="block bg-gray-100 pb-10 rounded-t-xl "
    >
        <nav class="flex bg-white py-4 justify-center space-x-2 rounded-t-xl ring-1 ring-gray-950/5 shadow-sm">
            @if($user->type === \App\Domain\User\UserType::INSTITUTION)
                <a
                    rel="noopener noreferrer"
                    class="flex items-center flex-shrink-0 px-5 py-2 border-b-4
                    border-gray-700 text-gray-400
                    dark:border-gray-700 dark:text-gray-400
                    hover:border-gray-700 hover:text-gray-400"
                >
                    Institutions
                </a>
            @endif
            @if($user->type === \App\Domain\User\UserType::CERTIFIER || $user->type === \App\Domain\User\UserType::OWNER)
            <a
                rel="noopener noreferrer"
                class="flex items-center flex-shrink-0 px-5 py-2 border-b-4
                border-gray-700 text-gray-400
                dark:border-gray-700 dark:text-gray-400
                hover:border-gray-700 hover:text-gray-400"
            >
                Mines
            </a>
            @endif
        </nav>
        <div class="mb-10 rounded-b-xl ring-1 ring-gray-950/5 bg-white p-5 shadow-sm">
            @if($user->type === \App\Domain\User\UserType::INSTITUTION)
                <livewire:user.view-user-institution :user="$user"/>
            @endif
            @if($user->type === \App\Domain\User\UserType::CERTIFIER || $user->type === \App\Domain\User\UserType::OWNER)
                <livewire:user.view-user-mine :user="$user"/>
            @endif
        </div>
    </div>
    @endif
</div>
