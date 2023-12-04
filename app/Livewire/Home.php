<?php

namespace App\Livewire;

use App\Domain\Mine\Factory\ValidateMineFactory;
use App\Domain\Mine\MineService;
use App\Domain\Mine\MineType;
use App\Domain\Status\Status;
use App\Models\Mine;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\HeaderActionsPosition;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Home extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected Builder $mines;
    protected MineService $mineService;
    protected ValidateMineFactory $validateMineFactory;

    public function boot(
        MineService $mineService,
        ValidateMineFactory $validateMineFactory,
    ): void
    {
        if(Auth::user()?->isAdmin()){
            $this->mines = Mine::query();
        }
        else if(Auth::user()){
            $this->mines = Auth::user()->mines()->getQuery();
        }
        else{
            $this->mines = Mine::query()->validated();
        }

        $this->mineService = $mineService;
        $this->validateMineFactory = $validateMineFactory;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->mines)
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->width(250)
                    ->height(250),
                TextColumn::make('name')->searchable(),
                TextColumn::make('score')->numeric(),
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
                    ->searchable()
                    ->visible(fn(): bool => (bool) Auth::user()),
                TextColumn::make('type')
                    ->badge()
                    ->searchable()
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Status::class)
                    ->attribute('status'),
                SelectFilter::make('type')
                    ->options(MineType::class)
                    ->attribute('type')
            ])
            ->headerActions([
                Action::make('create mine')
                    ->icon('heroicon-o-plus-circle')
                    ->url(fn (): string => route('mine.create'))
            ], position: HeaderActionsPosition::Bottom)
            ->actions([
                Action::make('view')
                    ->icon('heroicon-o-viewfinder-circle')
                    ->url(function (Mine $record): string {
                        if(Auth::user()?->isAdmin() || Auth::user() == null){
                            return route('mine.view', ['mine' => $record]);
                        }
                        return route('mine.view', ['mine' => $record->mine_id]);
                    })
                    ->visible(fn (Mine $record): bool =>
                        $record->isValidated() ||
                        Auth::user()?->isAdmin() ||
                        Auth::user()?->hasMine($record->mine_id) ||
                        Auth::user()?->id === $record->created_by
                    ),
                Action::make('report')
                    ->icon('heroicon-o-flag')
                    ->color('warning')
                    ->url(fn (Mine $record): string => route('mine.report', ['mine' => $record]))
                    ->visible(fn (Mine $record): bool => $record->isValidated()),
                Action::make('validate')
                    ->icon('heroicon-o-flag')
                    ->color('warning')
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
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->action(fn(Mine $record) => $record->delete())
                    ->requiresConfirmation()
                    ->visible(fn (Mine $record): bool => (bool) Auth::user()?->isAdmin()),
            ])->defaultPaginationPageOption(5);
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.home');
    }
}
