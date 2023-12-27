<?php

namespace App\Livewire\Institution;

use App\Domain\Institution\Factory\ValidateInstitutionFactory;
use App\Domain\Institution\InstitutionService;
use App\Domain\Institution\InstitutionType;
use App\Domain\SecurityService;
use App\Domain\Status\Status;
use App\Models;
use App\Models\Institution;
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

class HomeInstitution extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    private InstitutionService $institutionService;
    private ValidateInstitutionFactory $validateInstitutionFactory;
    private SecurityService $securityService;


    public function boot(
        InstitutionService $institutionService,
        ValidateInstitutionFactory $validateInstitutionFactory,
        SecurityService $securityService,

    ): void
    {
        $this->institutionService = $institutionService;
        $this->validateInstitutionFactory = $validateInstitutionFactory;
        $this->securityService = $securityService;
    }

    public function mount(): void
    {
        $this->securityService->checkAdmin();
    }

    private function getQuery(): Builder
    {
        if(Auth::user()?->isAdmin())
            return Institution::query()->with(['users']);
        else
            return Institution::query()
                ->where('status', Status::VALIDATED->value)
                ->orWhere('created_by', Auth::user()?->id)->whereNotNull('created_by');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('description')
                    ->wrap()->html(),
                TextColumn::make('type')
                    ->badge(),
                TextColumn::make('status')
                    ->visible(fn(): bool => (bool) Auth::user()?->isAdmin())
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
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(InstitutionType::class)
                    ->attribute('type'),
                SelectFilter::make('status')
                    ->options(Status::class)
                    ->attribute('status'),
            ])
            ->actions([
                Action::make('view')
                    ->icon('heroicon-o-viewfinder-circle')
                    ->url(fn (Models\Institution $record): string => route('institution.view', ['institution' => $record]))
                    ->visible(fn (Models\Institution $record): bool =>
                        $record->status === Status::VALIDATED ||
                        Auth::user()?->isAdmin() ||
                        Auth::user()?->id === $record->created_by
                    ),
                Action::make('edit')
                    ->color('warning')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (Models\Institution $record): string => route('institution.edit', ['institution' => $record]))
                    ->visible(fn (Models\Institution $record): bool =>
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
                    ->action(function(array $data, Models\Institution $record): void {
                        $this->institutionService->validate(
                            $this->validateInstitutionFactory->fromArray($data),
                            $record->id
                        );
                    })
                    ->visible(fn (Models\Institution $record): bool => $record->status === Status::FOR_VALIDATION && Auth::user()?->isAdmin()),
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn(Models\Institution $record) => $record->delete())
                    ->visible(fn (Models\Institution $record): bool => (bool) Auth::user()?->isAdmin()),
            ])
            ->headerActions([
                Action::make('Create institution')
                    ->icon('heroicon-o-plus-circle')
                    ->url(route('institution.create'))
            ], position: HeaderActionsPosition::Bottom);
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.institution.home-institution');
    }
}
