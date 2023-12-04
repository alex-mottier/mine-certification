<?php

namespace App\Livewire\Report;

use App\Domain\Report\Factory\StoreReportFactory;
use App\Domain\Report\ReportService;
use App\Domain\Report\ReportType;
use App\Domain\Status\Status;
use App\Models\Mine;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CreateReport extends Component implements HasForms
{
    use InteractsWithForms;

    public Mine $mine;

    private ReportService $reportService;
    private StoreReportFactory $storeReportFactory;

    public function boot(
        ReportService $reportService,
        StoreReportFactory $storeReportFactory,
    ): void
    {
        $this->reportService = $reportService;
        $this->storeReportFactory = $storeReportFactory;
    }

    public function mount(Mine $mine): void
    {
        $this->form->fill();
        $this->mine = $mine;
    }

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make("Report's information")->schema([
                TextInput::make('name')->required(),
                Repeater::make('report')
                    ->schema([
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
                            ->required()
                            ->columnSpan('2'),
                        FileUpload::make('attachments')
                            ->multiple()
                            ->image()
                            ->imageEditor(),
                    ])
                    ->columns(3),
            ]),
        ])->statePath('data');

    }

    public function report(): void
    {
        $this->reportService->store(
            $this->storeReportFactory->fromFront(
                $this->form->getRawState(),
                $this->mine->id,
                ReportType::REPORT,
                Status::FOR_VALIDATION
            )
        );

        $this->redirect(route('mine.view', $this->mine->id));
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.report.create-report');
    }
}
