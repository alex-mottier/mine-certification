<?php

namespace App\Livewire\Mine;

use App\Models\Mine;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\View\View;
use Livewire\Component;

class MineEvaluationAction extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    private Mine $mine;

    public function mount(Mine $mine = null): void
    {
        $this->mine = $mine;
    }

    public function evaluate(): Action
    {
        return Action::make('evaluate')
            ->url(fn() => route('mine.evaluate', ['mine' => $this->mine]));
    }

    public function render(): View
    {
        return view('livewire.mine.mine-evaluation-action');
    }
}
