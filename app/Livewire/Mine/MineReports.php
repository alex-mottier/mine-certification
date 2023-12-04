<?php

namespace App\Livewire\Mine;

use App\Domain\Status\Status;
use App\Models\Mine;
use App\Models\Report;
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
use Livewire\Component;

class MineReports extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    private Mine $mine;

    public function mount(Mine $mine = null): void
    {
        $this->mine = $mine;
    }

    private function getQuery(): Builder
    {
        if(Auth::user()?->isAdmin()){
            return $this->mine->reports()->getQuery();
        }

        return $this->mine
            ->reports()
            ->where('reports.status', Status::VALIDATED)
            ->getQuery();

    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->headerActions([
                Action::make('report this mine')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->url(route('mine.report', ['mine' => $this->mine]))
                    ->visible(fn(Report $report): bool => $this->mine->status === Status::VALIDATED),
            ], position: HeaderActionsPosition::Bottom)
            ->actions([
                Action::make('view')
                    ->icon('heroicon-o-viewfinder-circle')
                    ->url(fn (Report $record): string => route('report.view', ['report' => $record])),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Status::class)
                    ->attribute('status')
                    ->hidden(fn() => !auth()->user()?->isAdmin())
            ])
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (Status $state): string => match ($state) {
                        Status::CREATED => 'gray',
                        Status::FOR_VALIDATION => 'warning',
                        Status::VALIDATED => 'success',
                        Status::REFUSED => 'danger'
                    })->hidden(fn() => !auth()->user()?->isAdmin())
            ])->paginated(false);
    }

    public function render(): View
    {
        return view('livewire.mine.mine-reports');
    }
}
