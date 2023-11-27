<div class="py-10">
    <form wire:submit="update">
        {{ $this->form }}
        <div class="flex justify-center mt-5 fixed bottom-10 right-10"><x-button>Update User</x-button></div>
    </form>

    <x-filament-actions::modals />
</div>
