<?php

namespace App\Livewire;

use App\Domain\Status\Status;
use App\Models\Mine;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\HeaderActionsPosition;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Home extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(Mine::query())
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->width(250)
                    ->height(250),
                TextColumn::make('name')->searchable(),
                TextColumn::make('score')->numeric(),
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
                    ->searchable()
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Status::class)
                    ->attribute('status')
                    //->default(Status::VALIDATED->value)
                    //->hidden(fn() => !auth()->user()?->isAdmin())
            ])
            ->headerActions([
                Action::make('create mine')
                    ->icon('heroicon-o-plus-circle')
                    ->url(fn (): string => route('mine.create'))
            ], position: HeaderActionsPosition::Bottom)
            ->actions([
                Action::make('view')
                    ->icon('heroicon-o-viewfinder-circle')
                    ->url(fn (Mine $record): string => route('mine.view', ['mine' => $record]))
                    ->visible(fn (Mine $record): bool => $record->isValidated()),

                Action::make('report')
                    ->icon('heroicon-o-flag')
                    ->url(fn (Mine $record): string => route('mine.report', ['mine' => $record]))
                    ->visible(fn (Mine $record): bool => $record->isValidated()),
                Action::make('validate')
                    ->icon('heroicon-o-flag')
                    ->url(fn(Mine $record): string => route('mine.validate', ['mine' => $record]))
                    ->visible(fn (Mine $record): bool => $record->status === Status::FOR_VALIDATION && Auth::user()?->isAdmin()),
            ]);
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.home');
    }
}
