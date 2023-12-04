<?php

namespace App\Livewire\User;

use App\Models\Mine;
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

class ViewUserMine extends Component implements HasForms, HasTable
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
            ->query($this->user->mines()->getQuery())
            ->columns([
                TextColumn::make('name')
            ])
            ->actions([
                Action::make('view')
                    ->icon('heroicon-o-viewfinder-circle')
                    ->url(fn(Mine $record): string => route('mine.view', ['mine' => $record->mine_id]))
            ])
            ->paginated(false);
    }

    public function render(): View
    {
        return view('livewire.user.view-user-mine');
    }
}
