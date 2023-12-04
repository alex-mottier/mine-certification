<?php

namespace App\Livewire\User;

use App\Models\Institution;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\View\View;
use Livewire\Component;

class ViewUserInstitution extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public ?User $user;

    public function mount(User $user): void
    {
    $this->user = $user;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->user->institutions()->getQuery())
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('type')->badge()
            ])
            ->actions([
                Action::make('view')
                    ->icon('heroicon-o-viewfinder-circle')
                    ->url(fn(Institution $record): string => route('institution.view', ['institution' => $record->institution_id]))
            ])
            ->paginated(false);
    }

    public function render(): View
    {
        return view('livewire.user.view-user-institution');
    }
}
