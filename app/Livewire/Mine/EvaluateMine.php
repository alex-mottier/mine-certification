<?php

namespace App\Livewire\Mine;

use App\Domain\Report\Factory\StoreReportFactory;
use App\Domain\Report\Factory\UpdateReportFactory;
use App\Domain\Report\ReportService;
use App\Domain\Report\ReportType;
use App\Domain\Status\Status;
use App\Models\Chapter;
use App\Models\Criteria;
use App\Models\Mine;
use App\Models\Report;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

class EvaluateMine extends Component implements HasForms
{

    use InteractsWithForms;

    public ?array $data = [];
    private array $evaluations = [];
    private ?Report $evaluation;
    private Mine $mine;
    private ReportService $reportService;
    private StoreReportFactory $storeReportFactory;
    private UpdateReportFactory $updateReportFactory;

    public function boot(
        ReportService                 $reportService,
        StoreReportFactory $storeReportFactory,
        UpdateReportFactory $updateReportFactory,
    ): void
    {
        $this->reportService = $reportService;
        $this->storeReportFactory = $storeReportFactory;
        $this->updateReportFactory = $updateReportFactory;
    }

    public function mount(Mine $mine): void
    {
        $this->mine = $mine;
        /**
         * @var Report $evaluation
         */
        $evaluation = $mine->evaluation()->with('criteriaReports.criteria.chapter')->first();
        $this->evaluation = $evaluation;
        $criteriaReports = $evaluation?->criteriaReports;
        foreach ($criteriaReports??[] as $report){
            $this->evaluations[] = [
                'id' => $report->id,
                'chapter' => $report->criteria->chapter->id,
                'criteria' => $report->criteria->id,
                'comment' => $report->comment,
                'score' => $report->score,
                'attachments' => []
            ];
        }
        $this->form->fill(array_merge(['mine_id' => $mine->id],['name' => $evaluation?->name],['report' => $this->evaluations], ['validation' => $this->evaluation?->status->value ?? Status::CREATED->value]));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Evaluation')
                        ->schema([
                            TextInput::make('mine_id')->hidden(),
                            TextInput::make('name')->hidden(),
                            Radio::make('validation')
                                ->options([
                                    'for_validation' => 'Submit this evaluation for validation',
                                    'created' => 'Finish this evaluation later'
                                ])->default('created')->required(),
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
                                ->columns(),
                        ]),
                ])
            ->statePath('data');
    }

    public function update(): void
    {
        $form = $this->form->getRawState();
        $mine = Mine::query()->find($form['mine_id']);
        $evaluation = $mine->evaluation()->first();
        if($evaluation){
            $this->reportService->update(
                $this->updateReportFactory->fromArray($form),
                $evaluation->id
            );
        }
        else {
            $status = Status::from($form['validation']);
            if($evaluation->status === Status::VALIDATED){
                $status = Status::VALIDATED;
            }
            $this->reportService->store(
                $this->storeReportFactory->fromFront(
                    $form,
                    $form['mine_id'],
                    ReportType::EVALUATION,
                    $status)
            );
        }

        $this->redirect(route('mine.view', $form['mine_id']));
    }

    #[Layout('layouts.app')]
    public function render(): View
    {
        return view('livewire.mine.evaluate-mine');
    }
}
