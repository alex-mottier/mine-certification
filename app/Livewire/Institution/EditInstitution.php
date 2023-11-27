<?php

namespace App\Livewire\Institution;

use App\Domain\Institution\Factory\UpdateInstitutionFactory;
use App\Domain\Institution\InstitutionService;
use App\Domain\Institution\InstitutionType;
use App\Domain\Status\Status;
use App\Domain\User\UserType;
use App\Models\Institution;
use App\Models\Mine;
use App\Models\User;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class EditInstitution extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public ?Institution $institution;
    protected InstitutionService $institutionService;
    protected UpdateInstitutionFactory $updateInstitutionFactory;

    public function boot(
        InstitutionService $institutionService,
        UpdateInstitutionFactory $updateInstitutionFactory,
    ): void
    {
        $this->institutionService = $institutionService;
        $this->updateInstitutionFactory = $updateInstitutionFactory;
    }

    public function mount(Institution $institution): void
    {
        $this->institution = $institution;

        $this->form->fill(array_merge(
            $institution->toArray(),
            [
                'users' => $institution->users()->pluck('users.id')->toArray()
            ],
            [
                'mines' => $institution->mines()->pluck('mines.id')->toArray()
            ],
        ));
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([
                Wizard\Step::make('Information')
                    ->schema([
                        TextInput::make('id')
                            ->hidden(),
                        TextInput::make('name')
                            ->required(),
                        Select::make('type')
                            ->required()
                            ->options(InstitutionType::class),
                        RichEditor::make('description')
                            ->required()
                    ]),
                Wizard\Step::make('Users')
                    ->schema([
                        Select::make('users')
                            ->multiple()
                            ->options(
                                User::query()
                                    ->where('status', Status::VALIDATED->value)
                                    ->where('type', UserType::INSTITUTION->value)
                                    ->pluck('username', 'id')
                            )
                    ]),
                Wizard\Step::make('Mines')
                    ->schema([
                        Select::make('mines')
                            ->multiple()
                            ->options(
                                Mine::query()
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                    ])
                    ->visible(fn(): bool => (bool) Auth::user()?->isAdmin()),
            ])->submitAction(new HtmlString('<button class="text-black text-sm border-black font-bold border-1 py-2 px-4 rounded" type="submit">Submit</button>'))
        ])
            ->statePath('data');
    }

    public function update(): void
    {
        $form = $this->form->getRawState();

        $institution = $this->institutionService->update(
            $this->updateInstitutionFactory->fromArray($form)
        );

        $this->redirect(route('institution.view', $institution->getId()));
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.institution.edit-institution');
    }
}
