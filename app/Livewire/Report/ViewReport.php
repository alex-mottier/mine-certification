<?php

namespace App\Livewire\Report;

use App\Domain\Report\Factory\UpgradeReportCriteriaFactory;
use App\Domain\Report\Factory\UpgradeReportFactory;
use App\Domain\Report\ReportService;
use App\Domain\Report\ReportType;
use App\Domain\Status\Status;
use App\Models;
use App\Models\CriteriaReport;
use App\Models\Report;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ViewReport extends Component implements HasTable, HasForms, HasInfolists
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithInfolists;

    public Models\Report $report;
    private ReportService $reportService;
    private UpgradeReportFactory $upgradeReportFactory;
    private UpgradeReportCriteriaFactory $upgradeReportCriteriaFactory;

    public function boot(
        ReportService $reportService,
        UpgradeReportFactory $upgradeReportFactory,
        UpgradeReportCriteriaFactory $upgradeReportCriteriaFactory
    ): void
    {
        $this->reportService = $reportService;
        $this->upgradeReportFactory = $upgradeReportFactory;
        $this->upgradeReportCriteriaFactory = $upgradeReportCriteriaFactory;
    }

    public function mount(Models\Report $report): void
    {
        $this->report = $report;
    }

    private function getRecord(): Models\Report
    {
        /**
         * @var Report $report
         */
        $report = Report::with('createdBy')->find($this->report->id);;
        return $report;
    }

    public function reportInfolist(Infolist $infolist): Infolist
    {
        return $infolist->record($this->getRecord())->schema([
            Section::make("Report's information")->schema([
                TextEntry::make('name'),
                TextEntry::make('mine.name')
                    ->url(fn(Models\Report $record): string =>
                        route('mine.view', ['mine' => $record->mine()->first()])
                    ),
                TextEntry::make('createdBy.username')
                    ->url(fn(Models\Report $record): string =>
                        route('user.view', ['user' => $record->createdBy()->first()])
                    )->visible(fn(Models\Report $record): bool =>
                        (bool) $record->createdBy()->first()
                    ),
                TextEntry::make('status')
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
                    }),
                TextEntry::make('type')
                    ->badge()
                    ->color(fn (ReportType $state): string => match ($state) {
                        ReportType::REPORT => 'info',
                        ReportType::EVALUATION => 'second',
                    })->suffixActions([
                        \Filament\Infolists\Components\Actions\Action::make('validate')
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
                    ])
            ])->columns(3)
        ]);
    }

    public function table(Table $table):Table
    {
        return $table
            ->query($this->report->criteriaReports()->with('criteria.chapter, attachments')->getQuery())
            ->columns([
                TextColumn::make('criteria.chapter.name')
                    ->visible(fn(): bool => $this->report->type === ReportType::EVALUATION),
                TextColumn::make('criteria.name')
                    ->visible(fn(): bool => $this->report->type === ReportType::EVALUATION),
                TextColumn::make('comment')->wrap()->html(),
                TextColumn::make('score')
                    ->visible(fn(): bool => $this->report->type === ReportType::EVALUATION),
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
                    ->visible(fn(): bool => $this->report->type === ReportType::REPORT),
                TextColumn::make('attachments.filename')->listWithLineBreaks()
            ])
            ->actions([
                Action::make('download')
                    ->icon('heroicon-m-document-arrow-up')
                    ->url(
                        url: fn(CriteriaReport $record): string =>
                        route('report.criteriaReport.download', [
                            'report' => $record->report,
                            'criteriaReport' => $record
                        ]),
                        shouldOpenInNewTab: true)
                    ->visible(fn(CriteriaReport $record): bool => !$record->attachments()->get()->isEmpty()),
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
                    ->action(function(array $data, CriteriaReport $record): void {
                        $this->reportService->upgradeReportCriteria(
                            $this->upgradeReportCriteriaFactory->fromArray($data),
                            $record->id
                        );
                    })
                    ->visible(function(CriteriaReport $record): bool {
                        /**
                         * @var Collection<Models\User> $certifiers
                         */
                        $certifiers = $record->report->mine->certifiers()->pluck('users.id');

                        if(
                            $record->report->type === ReportType::REPORT &&
                            $record->status === Status::FOR_VALIDATION &&
                            $record->report->status === Status::VALIDATED &&
                            in_array(Auth::user()?->id, $certifiers->toArray())
                        ){
                            return true;
                        }

                        return false;
                    })
            ]);
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.report.view-report');
    }
}
