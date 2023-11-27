<?php

namespace App\Livewire\Mine;

use App\Domain\Mine\Factory\AssignCertifiersMineFactory;
use App\Domain\Mine\Factory\AssignInstitutionsMineFactory;
use App\Domain\Mine\Factory\StoreMineFactory;
use App\Domain\Mine\MineService;
use App\Domain\Report\Factory\StoreReportFactory;
use App\Domain\Report\ReportService;
use App\Domain\Report\ReportType;
use App\Domain\Status\Status;
use App\Models\Chapter;
use App\Models\Criteria;
use App\Models\Institution;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
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
    protected AssignInstitutionsMineFactory $assignInstitutionsMineFactory;

    public function boot(
        MineService                 $mineService,
        ReportService                 $reportService,
        StoreMineFactory            $storeMineFactory,
        StoreReportFactory $storeReportFactory,
        AssignCertifiersMineFactory $assignCertifiersMineFactory,
        AssignInstitutionsMineFactory $assignInstitutionsMineFactory,
    ): void
    {
        $this->mineService = $mineService;
        $this->reportService = $reportService;
        $this->storeMineFactory = $storeMineFactory;
        $this->storeReportFactory = $storeReportFactory;
        $this->assignCertifiersMineFactory = $assignCertifiersMineFactory;
        $this->assignInstitutionsMineFactory = $assignInstitutionsMineFactory;
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
                    Wizard\Step::make('Evaluation')
                        ->schema([
                            Radio::make('validation')
                                ->options([
                                    'for_validation' => 'Submit this evaluation for validation',
                                    'created' => 'Finish this evaluation later'
                                ]),
                            Repeater::make('report')
                                ->schema([
                                    Select::make('chapter')
                                        ->options(
                                            Chapter::query()
                                            ->pluck('name', 'id')
                                        )
                                        ->live()
                                        ->required(),
                                    Select::make('criteria')
                                        ->options(fn (Get $get): array => match ($get('chapter')) {
                                            default => Criteria::query()->where('chapter_id', $get('chapter'))
                                                ->pluck('name', 'id')->toArray(),
                                        })
                                        ->required(),
                                    RichEditor::make('comment')
                                        ->toolbarButtons([
                                            'blockquote',
                                            'bold',
                                            'bulletList',
                                            'codeBlock',
                                            'h2',
                                            'h3',
                                            'italic',
                                            'link',
                                            'orderedList',
                                            'strike',
                                            'underline',
                                        ])
                                        ->required(),
                                    FileUpload::make('attachments')
                                        ->multiple()
                                        ->image()
                                        ->imageEditor(),
                                    TextInput::make('score')
                                        ->numeric()
                                        ->step(0.1)
                                        ->minValue(1)
                                        ->maxValue(10)
                                        ->required(),
                                ])
                                ->columns(2),
                        ])->visible(fn(): bool => (bool) Auth::user()),
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

    public function create(): void
    {
        $form = $this->form->getState();
        $mine = $this->mineService->store(
            $this->storeMineFactory->fromArray($form)
        );

        if(array_key_exists('certifiers', $form) && $form['certifiers']){
            $this->mineService->assignCertifiers(
                $this->assignCertifiersMineFactory->fromArray($form['certifiers']),
                $mine->getId()
            );
        }
        if(array_key_exists('report', $form) && $form['report']) {
            $this->reportService->store(
                $this->storeReportFactory->fromFront(
                    $form,
                    $mine->getId(),
                    ReportType::EVALUATION,
                    Status::tryFrom($form['validation']) ?? Status::CREATED
                )
            );
        }

        if($form['owners']){
            $this->mineService->assignInstitutions(
                $this->assignInstitutionsMineFactory->fromArray($form['owners']),
                $mine->getId(),
            );
        }

        $this->redirect(route('mine.view', $mine->getId()));
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.mine.create-mine');
    }
}
