<?php

namespace App\Http\Controllers\Adviser;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApprovePefRequest;
use App\Http\Requests\ReturnPefRequest;
use App\Models\Pef;
use App\Services\AcademicHistoryService;
use App\Services\PefWorkflow;
use Illuminate\Http\Request;

class PefReviewController extends Controller
{
    public function __construct(
        protected PefWorkflow $workflow,
        protected AcademicHistoryService $historyService,
    ) {}

    public function queue(Request $request)
    {
        $pefs = Pef::with(['student.user', 'student.program', 'term'])
            ->where('status', Pef::STATUS_PENDING_ADVISING)
            ->whereHas('student', fn($q) => $q->where('adviser_id', $request->user()->id))
            ->latest()
            ->paginate(20);

        return view('adviser.queue', compact('pefs'));
    }

    public function show(Pef $pef)
    {
        $this->authorize('view', $pef);

        $pef->load([
            'items.section.subject',
            'items.section.schedules',
            'items.section.instructor',
            'items.section.room',
            'approvals.actor',
            'student.user',
            'student.program',
            'term',
        ]);

        $history = $this->historyService->getHistory($pef->student);
        $flagged = $this->historyService->getFlaggedSubjects($pef->student);

        return view('adviser.review', compact('pef', 'history', 'flagged'));
    }

    public function approve(ApprovePefRequest $request, Pef $pef)
    {
        $this->authorize('approve', $pef);
        $this->workflow->approve($pef, $request->user(), $request->remarks);
        return redirect()->route('adviser.queue')->with('success', 'PEF approved and forwarded to Admission.');
    }

    public function return(ReturnPefRequest $request, Pef $pef)
    {
        $this->authorize('return', $pef);
        $this->workflow->returnToStudent($pef, $request->user(), $request->remarks);
        return redirect()->route('adviser.queue')->with('success', 'PEF returned to student.');
    }

    public function reject(ReturnPefRequest $request, Pef $pef)
    {
        $this->authorize('reject', $pef);
        $this->workflow->reject($pef, $request->user(), $request->remarks);
        return redirect()->route('adviser.queue')->with('success', 'PEF rejected.');
    }
}
