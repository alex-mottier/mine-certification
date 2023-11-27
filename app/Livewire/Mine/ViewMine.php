<?php

namespace App\Livewire\Mine;

use App\Domain\Mine\Factory\ValidateMineFactory;
use App\Domain\Mine\MineService;
use App\Domain\Status\Status;
use App\Models\Mine;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ViewMine extends Component implements HasInfolists, HasForms, HasActions
{
    use InteractsWithInfolists;
    use InteractsWithForms;
    use InteractsWithActions;

    public Mine $mine;
    private MineService $mineService;
    private ValidateMineFactory $validateMineFactory;

    public function mount(Mine $mine): void
    {
        $this->mine = $mine;
    }

    public function boot(
        MineService $mineService,
        ValidateMineFactory $validateMineFactory,
    ): void
    {
        $this->mineService = $mineService;
        $this->validateMineFactory = $validateMineFactory;
    }

    public function mineInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->mine)
            ->schema([
                Section::make("Mine's information")
                    ->schema([
                        ImageEntry::make('image_path')
                            ->label("")
                            ->width(250)
                            ->height(250),
                        Section::make('')->schema([
                            TextEntry::make('name'),
                            TextEntry::make('phone_number'),
                            TextEntry::make('longitude'),
                            TextEntry::make('latitude'),
                            TextEntry::make('type')->badge(),
                            TextEntry::make('status')->suffixActions([
                                Action::make('validate')
                                    ->tooltip('Validate this mine')
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
                                    ->action(function(array $data, Mine $record): void {
                                        $this->mineService->validateMine(
                                            $this->validateMineFactory->withStatus(
                                                Status::from($data['status'])
                                            ),
                                            $record->id
                                        );
                                    })
                                    ->visible(fn (Mine $record): bool => $record->status === Status::FOR_VALIDATION && Auth::user()?->isAdmin()),
                                Action::make('edit')
                                    ->icon('heroicon-o-pencil-square')
                                    ->url(fn(Mine $record) => route('mine.edit', ['mine' => $record]))
                                    ->tooltip('Edit this mine')
                                    ->color('warning')
                                    ->visible(
                                        fn(Mine $record): bool =>
                                            Auth::user()?->isAdmin() ||
                                            Auth::user()?->id === $record->created_by ||
                                            Auth::user()?->hasMine($record->id)
                                    ),
                                Action::make('delete')
                                    ->icon('heroicon-o-trash')
                                    ->action(function(Mine $record) {$record->delete(); $this->redirect(route('home'));} )
                                    ->requiresConfirmation()
                                    ->color('danger')
                                    ->tooltip('Delete this mine')
                                    ->visible(
                                        fn(Mine $record): bool =>
                                            Auth::user()?->isAdmin() ||
                                            Auth::user()?->id === $record->created_by ||
                                            Auth::user()?->hasMine($record->id)
                                    )
                            ])
                            ->badge()
                            ->color(fn (Status $state): string => match ($state) {
                                    Status::CREATED => 'gray',
                                    Status::FOR_VALIDATION => 'warning',
                                    Status::VALIDATED => 'success',
                                    Status::REFUSED => 'danger'
                            }),
                        ])->columnSpan([
                            'sm' => 1,
                            'xl' => 3,
                            '2xl' => 4,
                        ])->columnStart([
                            'sm' => 2,
                            'xl' => 2,
                            '2xl' => 2,
                        ])->columns([
                            'sm' => 3,
                            'xl' => 3,
                            '2xl' => 3,
                        ]),
                    ])
                    ->columns([
                        'sm' => 2,
                        'xl' => 4,
                        '2xl' => 6,
                    ])
            ]);
    }


    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.mine.view-mine', [
            'mine' => $this->mine
        ]);
    }
}
