<?php

namespace App\Livewire\Institution;

use App\Models\Institution;
use App\Models\User;
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

class UserInstitution extends Component implements HasForms, HasTable
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
        return $this->institution->users()->getQuery();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                TextColumn::make('username')->searchable()
            ])
            ->actions([
                Action::make('view')
                    ->icon('heroicon-o-viewfinder-circle')
                    ->url(fn(User $record) => route('user.view', ['user' => $record]))
            ])
            ->paginated(false);
    }

    public function render():View
    {
        return view('livewire.institution.user-institution');
    }
}
