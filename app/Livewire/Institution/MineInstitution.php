<?php

namespace App\Livewire\Institution;

use App\Models\Institution;
use App\Models\Mine;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;

class MineInstitution extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public ?Institution $institution;

    public function mount(Institution $institution): void
    {
        $this->institution = $institution;
    }

    private function getQuery(): Builder
    {
        return $this->institution->mines()->getQuery();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                TextColumn::make('name')->searchable()
            ])
            ->actions([
                Action::make('view')
                    ->icon('heroicon-o-viewfinder-circle')
                    ->url(fn(Mine $record) => route('mine.view', ['mine' => $record]))
            ])
            ->paginated(false);
    }
    
    public function render(): View
    {
        return view('livewire.institution.mine-institution');
    }
}
