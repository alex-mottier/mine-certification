<?php

namespace App\Livewire\Mine;

use App\Models\Mine;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class MineOwners extends Component implements HasForms, HasTable
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
            ->query($this->mine->owners()->getQuery())
            ->columns([
                TextColumn::make('username')->searchable(),
                TextColumn::make('fullname')->searchable(),
            ])->actions([
                Action::make('view')
                    ->icon('heroicon-o-viewfinder-circle')
                    ->url(fn(User $record) => route('user.view', ['user' => $record->user_id]))
                    ->visible(fn(): bool => (bool) Auth::user()?->isAdmin())
            ])
            ->paginated(false);
    }

    public function render(): View
    {
        return view('livewire.mine.mine-owners');
    }
}
