<?php

namespace App\Livewire\Report;

use App\Domain\Report\Factory\UpgradeReportFactory;
use App\Domain\Report\ReportService;
use App\Domain\Report\ReportType;
use App\Domain\Status\Status;
use App\Models;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Report extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    private ReportService $reportService;
    private UpgradeReportFactory $upgradeReportFactory;

    public function boot(
        ReportService $reportService,
        UpgradeReportFactory $upgradeReportFactory,
    ): void
    {
        $this->reportService = $reportService;
        $this->upgradeReportFactory = $upgradeReportFactory;
    }

    private function getQuery(): Builder|Relation|null
    {
        if(Auth::user()?->isAdmin()){
            return Models\Report::query()->with('mine.certifiers');
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('mine.name')
                    ->url(fn(Models\Report $record): string =>
                        route('mine.view', ['mine' => $record->mine()->first()])
                    )
                    ->searchable(),
                TextColumn::make('score'),
                TextColumn::make('mine.certifiers.username')
                    ->searchable()
                    ->listWithLineBreaks()
                    ->bulleted(),
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
                    ->color(fn (ReportType $state): string => match ($state) {
                        ReportType::REPORT => 'info',
                        ReportType::EVALUATION => 'second',
                    })
                    ->searchable()
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Status::class)
                    ->attribute('status'),
                //->default(Status::VALIDATED->value)
                //->hidden(fn() => !auth()->user()?->isAdmin())
                SelectFilter::make('type')
                    ->options(ReportType::class)
                    ->attribute('type')
            ])
            ->actions([
                Action::make('view')
                    ->icon('heroicon-o-viewfinder-circle')
                    ->url(fn (Models\Report $record): string => route('report.view', ['report' => $record])),
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
                    ->action(function(array $data, Models\Report $record): void {
                        $this->reportService->upgrade(
                            $this->upgradeReportFactory->fromArray($data),
                            $record->id
                        );
                    })
                    ->visible(fn (Models\Report $record): bool => $record->status === Status::FOR_VALIDATION && Auth::user()?->isAdmin()),
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn(Models\Report $record) => $record->delete())
                    ->visible(fn (Models\Report $record): bool => Auth::user()?->isAdmin()),
            ]);
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.report.home');
    }
}
