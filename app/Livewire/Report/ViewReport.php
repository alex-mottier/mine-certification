<?php

namespace App\Livewire\Report;

use App\Models;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ViewReport extends Component implements HasTable, HasForms, HasInfolists
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithInfolists;

    public Models\Report $report;

    public function mount(Models\Report $report): void
    {
        $this->report = $report;
    }

    public function table(Table $table):Table
    {
        return $table
            ->query($this->report->criteriaReports()->with('criteria.chapter')->getQuery())
            ->columns([
                TextColumn::make('criteria.chapter.name'),
                TextColumn::make('criteria.name'),
                TextColumn::make('comment')->wrap()->html(),
                TextColumn::make('score'),
                ImageColumn::make('attachments.path')
            ])
            ->actions([

            ]);
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.report.view-report');
    }
}
