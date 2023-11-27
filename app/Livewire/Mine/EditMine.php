<?php

namespace App\Livewire\Mine;

use App\Domain\Mine\Factory\AssignCertifiersMineFactory;
use App\Domain\Mine\Factory\AssignInstitutionsMineFactory;
use App\Domain\Mine\Factory\UpdateMineFactory;
use App\Domain\Mine\MineService;
use App\Domain\Status\Status;
use App\Models\Institution;
use App\Models\Mine;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
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

class EditMine extends Component implements HasForms
{

    use InteractsWithForms;

    public ?array $data = [];

    protected MineService $mineService;
    protected UpdateMineFactory $updateMineFactory;

    protected AssignCertifiersMineFactory $assignCertifiersMineFactory;
    protected AssignInstitutionsMineFactory $assignInstitutionsMineFactory;

    public function boot(
        MineService                     $mineService,
        UpdateMineFactory               $updateMineFactory,
        AssignCertifiersMineFactory     $assignCertifiersMineFactory,
        AssignInstitutionsMineFactory   $assignInstitutionsMineFactory,
    ): void
    {
        $this->mineService = $mineService;
        $this->updateMineFactory = $updateMineFactory;
        $this->assignCertifiersMineFactory = $assignCertifiersMineFactory;
        $this->assignInstitutionsMineFactory = $assignInstitutionsMineFactory;
    }

    public function mount(Mine $mine): void
    {
        $certifiers = ['certifiers' => $mine->certifiers()->pluck('users.id')->toArray()];
        $owners = ['owners' => $mine->institutions()->pluck('institutions.id')->toArray()];
        $this->form->fill(array_merge($mine->toArray(), $certifiers, $owners));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Information')
                        ->schema([
                            TextInput::make('id')->hidden(),
                            TextInput::make('name')->required(),
                            TextInput::make('email')->email()->required(),
                            TextInput::make('phone_number')->tel()->required(),
                            TextInput::make('tax_number')->required(),
                            TextInput::make('longitude')->required(),
                            TextInput::make('latitude')->required(),
                            Radio::make('type')
                                ->required()
                                ->options([
                                    'artisanal' => 'Artisanal',
                                    'industrial' => 'Industrial',
                                    'cooperative' => 'Cooperative'
                                ])
                                ->inline(),
                            FileUpload::make('image_path')
                                ->label('Image')
                                ->image()
                                ->imageEditor()
                        ]),
                    Wizard\Step::make('Owners')
                        ->schema([
                            Select::make('owners')
                                ->multiple()
                                ->options(
                                    Institution::query()
                                        ->where('status', Status::VALIDATED->value)
                                        ->pluck('name', 'id')
                                )
                        ]),
                    Wizard\Step::make('Certifiers')
                        ->schema([
                            Select::make('certifiers')
                                ->multiple()
                                ->options(
                                    User::query()
                                        ->isCertifier()
                                        ->pluck('username', 'id')
                                )
                        ])
                        ->visible(fn(): bool => (bool) Auth::user()?->isAdmin()),
                ])->submitAction(new HtmlString('<button class="text-black text-sm border-black font-bold border-1 py-2 px-4 rounded" type="submit">Submit</button>'))
            ])
            ->statePath('data');
    }

    public function update(): void
    {
        $form = $this->form->getRawState();
        $mine = $this->mineService->update(
            $this->updateMineFactory->fromArray($form)
        );

        if(array_key_exists('certifiers', $form) && $form['certifiers']){
            $this->mineService->assignCertifiers(
                $this->assignCertifiersMineFactory->fromArray($form['certifiers']),
                $mine->getId()
            );
        }

        if($form['owners'])
            $this->mineService->assignInstitutions(
                $this->assignInstitutionsMineFactory->fromArray($form['owners']),
                $mine->getId(),
            );

        $this->redirect(route('mine.view', $mine->getId()));
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.mine.edit-mine');
    }
}
