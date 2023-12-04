<div>
    <div class="py-10">
        {{ $this->mineInfolist }}
    </div>
    <x-filament-actions::modals />
    <div
        x-data="{ tab: 'evaluation' }"
        class="block bg-gray-100 pb-10 rounded-t-xl "
    >
        <nav class="flex bg-white py-4 justify-center space-x-2 rounded-t-xl ring-1 ring-gray-950/5 shadow-sm">
            <a
                :class="{ 'border-gray-700 text-gray-400': tab === 'evaluation' }"
                x-on:click.prevent="tab = 'evaluation'"
                href="#"
                rel="noopener noreferrer"
                class="flex items-center flex-shrink-0 px-5 py-2 border-b-4
                dark:border-gray-700 dark:text-gray-400
                hover:border-gray-700 hover:text-gray-400"
            >
                Evaluation
            </a>
            <a
                :class="{ 'border-gray-700 text-gray-400': tab === 'reports' }"
                x-on:click.prevent="tab = 'reports'"
                href="#"
                rel="noopener noreferrer"
                class="flex items-center flex-shrink-0 px-5 py-2 border-b-4
                dark:border-gray-700 dark:text-gray-400
                hover:border-gray-700 hover:text-gray-400"
            >
                Reports
            </a>
            <a
                :class="{ 'border-gray-700 text-gray-400': tab === 'institutions' }"
                x-on:click.prevent="tab = 'institutions'"
                href="#"
                rel="noopener noreferrer"
                class="flex items-center flex-shrink-0 px-5 py-2 border-b-4
            dark:border-gray-700 dark:text-gray-400
            hover:border-gray-700 hover:text-gray-400"
            >
                Owners
            </a>
                @if(\Illuminate\Support\Facades\Auth::user()?->isAdmin())
            <a
                :class="{ 'border-gray-700 text-gray-400': tab === 'certifiers' }"
                x-on:click.prevent="tab = 'certifiers'"
                href="#"
                rel="noopener noreferrer"
                class="flex items-center flex-shrink-0 px-5 py-2 border-b-4
                dark:border-gray-700 dark:text-gray-400
                hover:border-gray-700 hover:text-gray-400"
            >
                Certifiers
            </a>
                @endif
        </nav>
        <div class="mb-10 rounded-b-xl ring-1 ring-gray-950/5 bg-white p-5 shadow-sm">
            @if(($mine->evaluation()->first() || $mine->isValidated()))
                <div x-show="tab === 'evaluation'" >
                    <livewire:mine.mine-evaluation :mine="$mine" />
                </div>
            @elseif(\Illuminate\Support\Facades\Auth::user()?->hasMine($mine->id))
                <div x-show="tab === 'evaluation'" class="flex justify-center">
                    <livewire:mine.mine-evaluation-action :mine="$mine" />
                </div>
            @endif
            <div x-show="tab === 'reports'"><livewire:mine.mine-reports :mine="$mine"/></div>
            <div x-show="tab === 'institutions'"><livewire:mine.mine-owners :mine="$mine"/></div>
            @if(\Illuminate\Support\Facades\Auth::user()?->hasMine($mine->id) || \Illuminate\Support\Facades\Auth::user()?->isAdmin())
                <div x-show="tab === 'certifiers'">
                    <livewire:mine.mine-certifiers :mine="$mine" />
                </div>
            @endif
        </div>
    </div>
</div>
