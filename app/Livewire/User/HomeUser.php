<?php

namespace App\Livewire\User;

use App\Domain\Status\Status;
use App\Domain\User\Factory\ValidateUserFactory;
use App\Domain\User\UserService;
use App\Domain\User\UserType;
use App\Models\User;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\HeaderActionsPosition;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class HomeUser extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected Builder $query;
    protected UserService $userService;
    protected ValidateUserFactory $validateUserFactory;

    public function boot(
        UserService $userService,
        ValidateUserFactory $validateUserFactory
    ): void
    {
        $this->query = User::query();
        $this->userService = $userService;
        $this->validateUserFactory = $validateUserFactory;
    }

    public function table(Table $table): Table
    {
        return $table
        ->query($this->query)
        ->columns([
            TextColumn::make('username')->searchable(),
            TextColumn::make('status')
                ->icon(fn (Status $state): string => match ($state) {
                    Status::CREATED => 'heroicon-o-plus-circle',
                    Status::FOR_VALIDATION => 'heroicon-o-clock',
                    Status::VALIDATED => 'heroicon-o-check-circle',
                    Status::REFUSED => 'heroicon-o-exclamation-circle'
                })
                ->badge()
                ->color(fn (Status $state): string => match ($state) {
                    Status::CREATED => 'gray',
                    Status::FOR_VALIDATION => 'warning',
                    Status::VALIDATED => 'success',
                    Status::REFUSED => 'danger'
                })
                ->searchable(),
            TextColumn::make('type')
                ->badge()
                ->color(fn (UserType $state): string => match ($state) {
                    UserType::ADMINISTRATOR => 'info',
                    UserType::CERTIFIER => 'second',
                    UserType::INSTITUTION => 'warning',
                })
                ->searchable()

        ])
        ->filters([
            SelectFilter::make('status')
                ->options(Status::class)
                ->attribute('status'),
            SelectFilter::make('type')
                ->options(UserType::class)
                ->attribute('type'),
            //->default(Status::VALIDATED->value)
            //->hidden(fn() => !auth()->user()?->isAdmin())
        ])
        ->headerActions([
            Action::make('create user')
                ->icon('heroicon-o-plus-circle')
                ->url(fn (): string => route('user.create'))
        ], position: HeaderActionsPosition::Bottom)
        ->actions([
            Action::make('view')
                ->icon('heroicon-o-viewfinder-circle')
                ->url(fn (User $record): string => route('user.view', ['user' => $record])),
            Action::make('edit')
                ->color('warning')
                ->icon('heroicon-o-pencil-square')
                ->url(fn (User $record): string => route('user.edit', ['user' => $record]))
                ->visible(fn (User $record): bool =>
                    Auth::user()?->isAdmin() ||
                    ($record->created_by &&
                        Auth::user()?->id === $record->created_by)
                ),
            Action::make('validate')
                ->icon('heroicon-o-flag')
                ->color('second')
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
                            Status::from($data['status'])
                        ),
                        $record->id
                    );
                })
                ->visible(fn (User $record): bool => $record->status === Status::FOR_VALIDATION && Auth::user()?->isAdmin()),
            Action::make('delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn(User $record) => $record->delete())
                ->visible(fn (User $record): bool => Auth::user()?->isAdmin()),
        ]);
    }


    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.user.user-home');
    }
}
