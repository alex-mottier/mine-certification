<?php

namespace App\Http\Controllers;

use App\Models\CriteriaReport;
use App\Models\Report;
use ZipArchive;

class DownloadController extends Controller
{
    
    public function criteriaReport(Report $report, CriteriaReport $criteriaReport)
    {
        $attachments = $criteriaReport->attachments()->get();

        $zip_file = $report->name.'.zip';
        $zip = new ZipArchive();
        $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($attachments as $attachment){
            $zip->addFile(storage_path('app/'.$attachment->path), $attachment->filename);
        }

        $zip->close();

        return response()->download($zip_file);
    }
}
