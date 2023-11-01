<?php

namespace App\Domain\Service\Report;

use App\Domain\DTO\Report\ReportDTO;
use App\Domain\DTO\Report\SearchReportDTO;
use App\Domain\DTO\Report\StoreReportDTO;
use App\Domain\DTO\Report\UpdateReportDTO;
use App\Domain\DTO\Report\UpgradeReportDTO;
use App\Domain\Factory\Report\ReportDTOFactory;
use App\Domain\Status\Status;
use App\Domain\Type\ReportType;
use App\Exceptions\Auth\UnauthorizedException;
use App\Exceptions\Report\ReportNotFoundException;
use App\Exceptions\Status\BadStatusException;
use App\Models\Attachment;
use App\Models\CriteriaReport;
use App\Models\Mine;
use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\AuthManager;

readonly class ReportService
{
    private ?User $authUser;
    public function __construct(
        protected ReportDTOFactory $factory,
        protected AuthManager $authManager,
    )
    {
        $this->authUser = $this->authManager->guard('sanctum')->user();
    }

    /**
     * @param SearchReportDTO $search
     * @return ReportDTO[]
     */
    public function list(SearchReportDTO $search): array
    {
        $reports = [];
        $query = Report::query();

        foreach ($query->get() as $report){
            $reports[] = $this->factory->fromModel($report);
        }

        return $reports;
    }


    public function store(StoreReportDTO $store): ReportDTO
    {
        if($store->getType() === ReportType::REPORT){
            $store->setStatus(Status::FOR_VALIDATION);
        }

        /**
         * @var Report $report
         */
        $report = Report::query()->create($store->jsonSerialize());

        foreach ($store->getCriterias() as $criteria) {
            $attributes = array_merge($criteria->jsonSerialize(), ['report_id' => $report->id]);
            /**
             * @var CriteriaReport $criteriaReport
             */
            $criteriaReport = CriteriaReport::query()->create($attributes);
            foreach ($criteria->getAttachments() as $attachment){
                $file = $attachment->storeAs(
                    'attachments',
                    $attachment->getClientOriginalName()
                );
                $criteriaReport->attachments()->create([
                    'filename' => $attachment->getClientOriginalName(),
                    'path' => $file,
                ]);
            }
        }
        return $this->factory->fromModel($report);
    }

    public function update(UpdateReportDTO $update, int $reportId): ReportDTO
    {
        /**
         * @var Report|null $report
         */
        $report = Report::query()->find($reportId);
        if(!$report){
            throw new ReportNotFoundException();
        }

        if($this->authUser->id !== $report->created_by){
            throw new UnauthorizedException();
        }
        $report->update($update->jsonSerialize());

        foreach ($update->getCriterias() as $criteria) {
            $attributes = array_merge($criteria->jsonSerialize(), ['report_id' => $report->id]);
            /**
             * @var CriteriaReport $criteriaReport
             */
            $criteriaReport = CriteriaReport::query()
                ->updateOrCreate([
                    'report_id' => $report->id,
                    'criteria_id' => $criteria->getCriteriaId(),
                ],
                    $attributes
                );

            foreach ($criteria->getAttachments() as $attachment){
                $modelAttachment = Attachment::query()
                        ->where('filename',$attachment->getClientOriginalName())
                        ->where('criteria_report_id', $criteriaReport->id)
                        ->first();
                if(!$modelAttachment){
                    $file = $attachment->storeAs(
                        'attachments',
                        $attachment->getClientOriginalName()
                    );
                    $criteriaReport->attachments()->create([
                        'filename' => $attachment->getClientOriginalName(),
                        'path' => $file,
                    ]);
                }
            }
        }
        return $this->factory->fromModel($report);
    }

    public function upgrade(UpgradeReportDTO $upgrade, int $reportId): ReportDTO
    {
        /**
         * @var Report $report
         */
        $report = Report::query()->find($reportId);
        if(!$report){
            throw new ReportNotFoundException();
        }

        match($upgrade->getStatus()){
            Status::VALIDATED, Status::REFUSED =>
                $this->checksForValidatedRefused($upgrade->getUser(), $report)
            ,
            Status::FOR_VALIDATION => $this->checksForValidation($upgrade, $report),
            default => throw new BadStatusException(
                "Status should be 'validated', 'refused' or 'for_validation'"
            )
        };

        if($upgrade->getStatus() === Status::VALIDATED){
            $this->calculateScore();
        }

        $report->status = $upgrade->getStatus();
        $report->save();

        return $this->factory->fromModel($report);
    }

    private function isCertifierOrOwner(array $certifiers, User $user, Report $report): bool
    {
        foreach ($certifiers as $certifier){
            if($certifier->id === $user->id){
                return true;
            }
        }

        /**
         * @var User $owner
         */
        $owner = $report->createdBy()->first();
        if($user->id === $owner->id){
            return true;
        }

        return false;
    }

    private function checksForValidatedRefused(User $user, Report $report): void
    {
        if(!$user->isAdmin() || $report->status !== Status::FOR_VALIDATION){
            throw new UnauthorizedException();
        }
    }

    private function checksForValidation(UpgradeReportDTO $upgrade, Report $report): void
    {
        /**
         * @var Mine $mine
         */
        $mine = $report->mine()->first();

        /**
         * @var User[] $certifiers
         */
        $certifiers = $mine->certifiers()->get()->toArray();
        if(
            !$this->isCertifierOrOwner($certifiers, $upgrade->getUser(), $report) &&
            ($mine->status !== Status::CREATED ||
            $mine->status !== Status::REFUSED)
        ){
            throw new UnauthorizedException();
        }
    }

    private function calculateScore(): void
    {
        //TODO Calculate score
    }
}
