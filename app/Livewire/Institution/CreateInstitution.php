<?php

namespace App\Livewire\Institution;

use App\Domain\Institution\Factory\StoreInstitutionFactory;
use App\Domain\Institution\InstitutionService;
use App\Domain\Institution\InstitutionType;
use App\Domain\Status\Status;
use App\Domain\User\UserType;
use App\Models\User;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CreateInstitution extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    private InstitutionService $institutionService;
    private StoreInstitutionFactory $storeInstitutionFactory;

    public function boot(
        InstitutionService $institutionService,
        StoreInstitutionFactory $storeInstitutionFactory,
    ): void
    {
        $this->institutionService = $institutionService;
        $this->storeInstitutionFactory = $storeInstitutionFactory;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([
                Wizard\Step::make('Information')
                    ->schema([
                        TextInput::make('name')->required(),
                        Select::make('type')->required()->options([
                            InstitutionType::BROKER->value => InstitutionType::BROKER->name,
                            InstitutionType::CUSTOMS->value=> InstitutionType::CUSTOMS->name,
                            InstitutionType::MINE->value => InstitutionType::MINE->name,
                            InstitutionType::ONG->value => InstitutionType::ONG->name,
                            InstitutionType::PROCESSING_PLANT->value => InstitutionType::PROCESSING_PLANT->name,
                            InstitutionType::REFINERY->value => InstitutionType::REFINERY->name,
                        ]),
                        RichEditor::make('description')->required()
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
            ])->submitAction(new HtmlString('<button class="text-black text-sm border-black font-bold border-1 py-2 px-4 rounded" type="submit">Submit</button>'))
        ])
            ->statePath('data');
    }

    public function create(): void
    {
        $form = $this->form->getState();
        $institution = $this->institutionService->store(
            $this->storeInstitutionFactory->fromArray($form)
        );

        $this->redirect(route('institution.view', $institution->getId()));
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.institution.create-institution');
    }
}
