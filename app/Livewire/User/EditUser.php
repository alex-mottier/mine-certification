<?php

namespace App\Livewire\User;

use App\Domain\SecurityService;
use App\Domain\User\Factory\UpdateUserFactory;
use App\Domain\User\UserService;
use App\Domain\User\UserType;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class EditUser extends Component implements HasForms
{
    use InteractsWithForms;

    public ?User $user;
    public ?array $data = [];

    protected UserService $userService;
    protected UpdateUserFactory $updateUserFactory;
    private SecurityService $securityService;

    public function mount(User $user): void
    {
        $this->securityService->checkAdmin();
        $this->user = $user;
        $this->form->fill($this->user->toArray());

    }

    public function boot(
        UserService $userService,
        UpdateUserFactory $updateUserFactory,
        SecurityService $securityService,
    ): void
    {
        $this->userService = $userService;
        $this->updateUserFactory = $updateUserFactory;
        $this->securityService = $securityService;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make("User's information")->schema([
                TextInput::make('id')->hidden(),
                TextInput::make('username'),
                TextInput::make('fullname'),
                TextInput::make('email')
                    ->email(),
                TextInput::make('password')
                    ->password(),
                TextInput::make('longitude'),
                TextInput::make('latitude'),
                Select::make('type')
                    ->options(UserType::class),
            ])
        ])->statePath('data');
    }

    public function update(): void
    {
        $form = $this->form->getRawState();

        $user = $this->userService->update(
            $this->updateUserFactory->fromArray($form)
        );

        $this->redirect(route('user.view', $user->getId()));
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.user.edit-user');
    }
}
