<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Pef;

class AssessmentService
{
    public function compute(Pef $pef): Assessment
    {
        $tuitionPerUnit = (float) config('fees.tuition_per_unit', 450.00);
        $miscConfig     = config('fees.misc', []);

        $tuition   = $pef->total_units * $tuitionPerUnit;
        $miscTotal = collect($miscConfig)->sum('amount');
        $total     = $tuition + $miscTotal;

        return Assessment::updateOrCreate(
            ['pef_id' => $pef->id],
            [
                'tuition'   => $tuition,
                'misc_fees' => $miscConfig,
                'total'     => $total,
            ]
        );
    }
}
