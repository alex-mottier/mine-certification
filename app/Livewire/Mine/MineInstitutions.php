<?php

namespace App\Livewire\Mine;

use App\Models\Institution;
use App\Models\Mine;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\View\View;
use Livewire\Component;

class MineInstitutions extends Component implements HasForms, HasTable
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
        return $table
            ->query($this->mine->institutions()->getQuery())
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('type')->badge()->searchable()
            ])->actions([
                Action::make('view')
                    ->icon('heroicon-o-viewfinder-circle')
                    ->url(fn(Institution $record) => route('institution.view', ['institution' => $record]))
            ])
            ->paginated(false);
    }

    public function render(): View
    {
        return view('livewire.mine.mine-institutions');
    }
}
