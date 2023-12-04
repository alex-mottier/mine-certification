<?php

namespace App\Livewire\Mine;

use App\Models\Mine;
use App\Models\Report;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\HeaderActionsPosition;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class MineEvaluation extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    private Mine $mine;

    public function mount(Mine $mine = null): void
    {
        $this->mine = $mine;
    }

    public function table(Table $table): Table
    {
        $evaluation = $this->mine->evaluation()->first();
        /**
         * @var Report $evaluation
         */
        $query = $evaluation->criteriaReports();

        return $table
            ->query($query->getQuery())
            ->columns([
                TextColumn::make('criteria.chapter.name')->searchable(),
                TextColumn::make('criteria.name')->searchable(),
                TextColumn::make('comment')->wrap()->html(),
                TextColumn::make('score')->numeric(),
            ])
            ->headerActions([
                Action::make('Evaluate')
                    ->icon('heroicon-o-play-pause')
                    ->color('warning')
                    ->url(route('mine.evaluate', ['mine' => $this->mine]))
                    ->visible(fn(): bool => (bool) Auth::user()?->hasMine($this->mine->id))
            ], position: HeaderActionsPosition::Bottom)->paginated(false);
    }

    public function render(): View
    {
        return view('livewire.mine.mine-evaluation');
    }
}
