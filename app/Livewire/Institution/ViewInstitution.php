<?php

namespace App\Livewire\Institution;

use App\Domain\Institution\Factory\ValidateInstitutionFactory;
use App\Domain\Institution\InstitutionService;
use App\Domain\Status\Status;
use App\Models\Institution;
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

class ViewInstitution extends Component implements HasInfolists, HasForms
{
    use InteractsWithInfolists;
    use InteractsWithForms;

    public ?Institution $institution;
    protected InstitutionService $institutionService;
    protected ValidateInstitutionFactory $validateInstitutionFactory;

    public function boot(
        InstitutionService $institutionService,
        ValidateInstitutionFactory $validateInstitutionFactory,
    ): void
    {
        $this->institutionService = $institutionService;
        $this->validateInstitutionFactory = $validateInstitutionFactory;
    }

    public function mount(Institution $institution): void
    {
        $this->institution = $institution;
    }

    public function institutionInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->institution)
            ->schema([
                Section::make("Institution's information")
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('type')
                            ->badge(),
                        TextEntry::make('description')
                            ->html(),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (Status $state): string => match ($state) {
                                Status::CREATED => 'gray',
                                Status::FOR_VALIDATION => 'warning',
                                Status::VALIDATED => 'success',
                                Status::REFUSED => 'danger'
                            })->suffixActions([
                                Action::make('validate')
                                    ->tooltip('Validate this institution')
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
                                    ->action(function(array $data, Institution $record): void {
                                        $this->institutionService->validate(
                                            $this->validateInstitutionFactory->fromArray(
                                                $data
                                            ),
                                            $record->id
                                        );
                                    })
                                    ->visible(fn (Institution $record): bool => $record->status === Status::FOR_VALIDATION && Auth::user()?->isAdmin()),
                                Action::make('edit')
                                    ->icon('heroicon-o-pencil-square')
                                    ->url(fn(Institution $record) => route('institution.edit', ['institution' => $record]))
                                    ->tooltip('Edit this institution')
                                    ->color('warning')
                                    ->visible(
                                        fn(Institution $record): bool =>
                                            Auth::user()?->isAdmin() ||
                                            (Auth::user()?->id === $record->created_by &&
                                            $record->created_by)
                                    ),
                                Action::make('delete')
                                    ->icon('heroicon-o-trash')
                                    ->action(function(Institution $record) {$record->delete(); $this->redirect(route('institution.home'));} )
                                    ->requiresConfirmation()
                                    ->color('danger')
                                    ->tooltip('Delete this institution')
                                    ->visible(
                                        fn(Institution $record): bool =>
                                            Auth::user()?->isAdmin() ||
                                            (Auth::user()?->id === $record->created_by &&
                                                $record->created_by)
                                    )
                            ]),
                    ])->columns()
            ]);
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.institution.view-institution',[
            'institution' => $this->institution
        ]);
    }
}
