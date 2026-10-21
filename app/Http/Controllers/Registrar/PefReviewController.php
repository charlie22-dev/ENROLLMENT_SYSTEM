<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApprovePefRequest;
use App\Http\Requests\ReturnPefRequest;
use App\Models\Pef;
use App\Services\PefWorkflow;
use Illuminate\Http\Request;

class PefReviewController extends Controller
{
    public function __construct(protected PefWorkflow $workflow) {}

    public function queue(Request $request)
    {
        $pefs = Pef::with(['student.user', 'student.program', 'term'])
            ->where('status', Pef::STATUS_PENDING_REGISTRAR)
            ->latest()
            ->paginate(20);

        return view('registrar.queue', compact('pefs'));
    }

    public function show(Pef $pef)
    {
        $this->authorize('view', $pef);

        $pef->load([
            'items.section.subject',
            'items.section.schedules',
            'approvals.actor',
            'student.user',
            'student.program',
            'term',
        ]);

        return view('registrar.review', compact('pef'));
    }

    public function approve(ApprovePefRequest $request, Pef $pef)
    {
        $this->authorize('approve', $pef);
        $this->workflow->approve($pef, $request->user(), $request->remarks);
        return redirect()->route('registrar.queue')->with('success', 'PEF approved and forwarded to Cashier.');
    }

    public function return(ReturnPefRequest $request, Pef $pef)
    {
        $this->authorize('return', $pef);
        $this->workflow->returnToStudent($pef, $request->user(), $request->remarks);
        return redirect()->route('registrar.queue')->with('success', 'PEF returned to student.');
    }
}
