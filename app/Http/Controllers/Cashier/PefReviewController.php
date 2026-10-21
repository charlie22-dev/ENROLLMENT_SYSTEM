<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecordPaymentRequest;
use App\Models\Payment;
use App\Models\Pef;
use App\Services\AssessmentService;
use App\Services\PefWorkflow;
use Illuminate\Http\Request;

class PefReviewController extends Controller
{
    public function __construct(
        protected PefWorkflow $workflow,
        protected AssessmentService $assessmentService,
    ) {}

    public function queue(Request $request)
    {
        $pefs = Pef::with(['student.user', 'student.program', 'term'])
            ->where('status', Pef::STATUS_PENDING_CASHIER)
            ->latest()
            ->paginate(20);

        return view('cashier.queue', compact('pefs'));
    }

    public function show(Pef $pef)
    {
        $this->authorize('view', $pef);

        $pef->load([
            'items.section.subject',
            'approvals.actor',
            'student.user',
            'student.program',
            'term',
            'payments.receiver',
        ]);

        $assessment = $this->assessmentService->compute($pef);

        return view('cashier.review', compact('pef', 'assessment'));
    }

    public function storePayment(RecordPaymentRequest $request, Pef $pef)
    {
        $this->authorize('approve', $pef);

        Payment::create([
            'pef_id'      => $pef->id,
            'amount'      => $request->amount,
            'or_number'   => $request->or_number,
            'received_by' => $request->user()->id,
            'paid_at'     => now(),
        ]);

        $this->workflow->approve($pef, $request->user());

        return redirect()->route('cashier.queue')
            ->with('success', "Payment recorded. Student {$pef->student->student_no} is now enrolled.");
    }
}
