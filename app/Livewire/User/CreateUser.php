<?php

namespace App\Livewire\User;

use App\Domain\User\Factory\StoreUserFactory;
use App\Domain\User\UserService;
use App\Domain\User\UserType;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CreateUser extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected UserService $userService;
    protected StoreUserFactory $storeUserFactory;

    public function boot(
        UserService $userService,
        StoreUserFactory $storeUserFactory,
    ): void
    {
        $this->userService = $userService;
        $this->storeUserFactory = $storeUserFactory;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make("User's information")->schema([
                TextInput::make('username')
                    ->required(),
                TextInput::make('fullname')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->required()
                    ->password(),
                Select::make('type')
                    ->required()
                    ->options(UserType::class),
            ])
        ])->statePath('data');
    }

    public function create(): void
    {
        $form = $this->form->getState();
        $user = $this->userService->store(
            $this->storeUserFactory->fromArray($form)
        );

        $this->redirect(route('user.view', $user->getId()));
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.user.create-user');
    }
}
