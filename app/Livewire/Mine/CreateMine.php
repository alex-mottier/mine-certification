<?php

namespace App\Livewire\Mine;

use App\Domain\Mine\Factory\AssignCertifiersMineFactory;
use App\Domain\Mine\Factory\AssignUsersMineFactory;
use App\Domain\Mine\Factory\StoreMineFactory;
use App\Domain\Mine\MineService;
use App\Domain\Report\Factory\StoreReportFactory;
use App\Domain\Report\ReportService;
use App\Domain\User\UserType;
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

class CreateMine extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected MineService $mineService;
    protected ReportService $reportService;
    protected StoreMineFactory $storeMineFactory;
    protected StoreReportFactory $storeReportFactory;
    protected AssignCertifiersMineFactory $assignCertifiersMineFactory;
    protected AssignUsersMineFactory $assignUsersMineFactory;

    public function boot(
        MineService                 $mineService,
        ReportService               $reportService,
        StoreMineFactory            $storeMineFactory,
        StoreReportFactory          $storeReportFactory,
        AssignCertifiersMineFactory $assignCertifiersMineFactory,
        AssignUsersMineFactory      $assignInstitutionsMineFactory,
    ): void
    {
        $this->mineService = $mineService;
        $this->reportService = $reportService;
        $this->storeMineFactory = $storeMineFactory;
        $this->storeReportFactory = $storeReportFactory;
        $this->assignCertifiersMineFactory = $assignCertifiersMineFactory;
        $this->assignUsersMineFactory = $assignInstitutionsMineFactory;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Information')
                        ->schema([
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
                                    User::query()
                                        ->where('type', UserType::OWNER->value)
                                        ->pluck('username', 'id')
                                )
                        ])->visible(fn(): bool => (bool) Auth::user()?->isAdmin()),
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

    public function create(): void
    {
        $form = $this->form->getState();
        $mine = $this->mineService->store(
            $this->storeMineFactory->fromArray($form)
        );

        $users = [];

        if(array_key_exists('certifiers', $form) && $form['certifiers']){
            $users = array_merge($users, $form['certifiers']);
        }

        if(array_key_exists('owners', $form) && $form['owners']){
            $users = array_merge($users, $form['owners']);
        }

        $this->mineService->assignUsers(
            $this->assignUsersMineFactory->fromArray($users),
            $mine->getId(),
        );

        $this->redirect(route('mine.view', $mine->getId()));
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.mine.create-mine');
    }
}
