<?php

namespace App\Livewire\User;

use App\Domain\Status\Status;
use App\Domain\User\Factory\ValidateUserFactory;
use App\Domain\User\UserService;
use App\Models\User;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ViewUser extends Component implements HasInfolists, HasForms
{
    use InteractsWithForms;
    use InteractsWithInfolists;

    public ?User $user;

    protected UserService $userService;
    protected ValidateUserFactory $validateUserFactory;

    public function boot(
        UserService $userService,
        ValidateUserFactory $validateUserFactory,
    ): void
    {
        $this->userService = $userService;
        $this->validateUserFactory = $validateUserFactory;
    }

    public function mount(User $user): void
    {
        $this->user = $user;
    }


    public function userInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->user)
            ->schema([
                Section::make("User's information")
                    ->schema([
                        TextEntry::make('username'),
                        TextEntry::make('fullname'),
                        TextEntry::make('email'),
                        TextEntry::make('type')->badge(),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (Status $state): string => match ($state) {
                                Status::CREATED => 'gray',
                                Status::FOR_VALIDATION => 'warning',
                                Status::VALIDATED => 'success',
                                Status::REFUSED => 'danger'
                            }),
                        TextEntry::make('longitude'),
                        TextEntry::make('latitude')->suffixActions([
                            Action::make('validate')
                                ->tooltip('Validate this user')
                                ->icon('heroicon-o-flag')
                                ->color('third')
                                ->form([
                                    Radio::make('status')
                                        ->options([
                                            'validated' => 'Validated',
                                            'refused' => 'Refused'
                                        ])
                                        ->inline()
                                        ->required()
                                ])
                                ->action(function(array $data, User $record): void {
                                    $this->userService->validateUser(
                                        $this->validateUserFactory->fromStatus(
                                            Status::tryFrom($data['status'])
                                        ),
                                        $record->id
                                    );
                                })
                                ->visible(fn (User $record): bool => $record->status === Status::FOR_VALIDATION && Auth::user()?->isAdmin()),
                            Action::make('edit')
                                ->icon('heroicon-o-pencil-square')
                                ->url(fn(User $record) => route('user.edit', ['user' => $record]))
                                ->tooltip('Edit this user')
                                ->color('warning')
                                ->visible(
                                    fn(User $record): bool =>
                                        Auth::user()?->isAdmin() ||
                                        (Auth::user()?->id === $record->created_by && $record->created_by)
                                ),
                            Action::make('delete')
                                ->icon('heroicon-o-trash')
                                ->action(function(User $record) {$record->delete(); $this->redirect(route('users'));} )
                                ->requiresConfirmation()
                                ->color('danger')
                                ->tooltip('Delete this user')
                                ->visible(
                                    fn(User $record): bool =>
                                        Auth::user()?->isAdmin() ||
                                        (Auth::user()?->id === $record->created_by && $record->created_by)
                                )
                        ]),
                    ])->columns(4)
            ]);
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.user.view-user',[
            'user' => $this->user
        ]);
    }
}
