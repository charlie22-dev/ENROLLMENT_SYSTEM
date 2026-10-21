<?php

namespace Tests\Feature;

use App\Exceptions\InvalidTransitionException;
use App\Models\Pef;
use App\Models\PefItem;
use App\Models\Program;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use App\Services\PefValidator;
use App\Services\PefWorkflow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PefWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $studentUser;
    protected Student $student;
    protected User $adviser;
    protected User $admission;
    protected User $registrar;
    protected User $cashier;
    protected Term $term;
    protected Section $section1;
    protected Section $section2;
    protected PefWorkflow $workflow;
    protected PefValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adviser = User::factory()->withRole('adviser')->create();
        $this->admission = User::factory()->withRole('admission')->create();
        $this->registrar = User::factory()->withRole('registrar')->create();
        $this->cashier = User::factory()->withRole('cashier')->create();

        $program = Program::factory()->create();

        $this->studentUser = User::factory()->withRole('student')->create();
        $this->student = Student::create([
            'user_id'    => $this->studentUser->id,
            'student_no' => '2024-TEST1',
            'program_id' => $program->id,
            'year_level' => 1,
            'adviser_id' => $this->adviser->id,
            'status'     => 'active',
        ]);

        $this->term = Term::create([
            'school_year'      => '2024-2025',
            'semester'         => '1st',
            'enrollment_start' => now()->subDays(2),
            'enrollment_end'   => now()->addDays(10),
            'is_active'        => true,
        ]);

        $subj1 = Subject::create(['code' => 'TEST101', 'title' => 'Subject 1', 'units' => 6, 'type' => 'lec']);
        $subj2 = Subject::create(['code' => 'TEST102', 'title' => 'Subject 2', 'units' => 6, 'type' => 'lec']);

        $this->section1 = Section::create([
            'subject_id'     => $subj1->id,
            'term_id'        => $this->term->id,
            'section_code'   => 'A',
            'capacity'       => 30,
            'enrolled_count' => 0,
        ]);

        $this->section2 = Section::create([
            'subject_id'     => $subj2->id,
            'term_id'        => $this->term->id,
            'section_code'   => 'A',
            'capacity'       => 30,
            'enrolled_count' => 0,
        ]);

        $this->workflow = new PefWorkflow();
        $this->validator = new PefValidator();
    }

    public function test_student_can_submit_valid_pef(): void
    {
        $pef = Pef::create([
            'student_id'  => $this->student->id,
            'term_id'     => $this->term->id,
            'status'      => Pef::STATUS_DRAFT,
            'total_units' => 12,
        ]);

        PefItem::create(['pef_id' => $pef->id, 'section_id' => $this->section1->id]);
        PefItem::create(['pef_id' => $pef->id, 'section_id' => $this->section2->id]);

        $this->workflow->submit($pef, $this->studentUser, $this->validator);

        $this->assertEquals(Pef::STATUS_PENDING_ADVISING, $pef->fresh()->status);
        $this->assertEquals(1, $this->section1->fresh()->enrolled_count);
        $this->assertEquals(1, $this->section2->fresh()->enrolled_count);
        $this->assertDatabaseHas('pef_approvals', [
            'pef_id' => $pef->id,
            'stage'  => 'student',
            'action' => 'submitted',
        ]);
    }

    public function test_adviser_can_approve_pef(): void
    {
        $pef = Pef::create([
            'student_id'  => $this->student->id,
            'term_id'     => $this->term->id,
            'status'      => Pef::STATUS_PENDING_ADVISING,
            'total_units' => 12,
        ]);

        $this->workflow->approve($pef, $this->adviser, 'Adviser Approved');

        $this->assertEquals(Pef::STATUS_PENDING_ADMISSION, $pef->fresh()->status);
        $this->assertDatabaseHas('pef_approvals', [
            'pef_id'  => $pef->id,
            'stage'   => 'advising',
            'action'  => 'approved',
            'remarks' => 'Adviser Approved',
        ]);
    }

    public function test_admission_and_registrar_can_advance_pef(): void
    {
        $pef = Pef::create([
            'student_id'  => $this->student->id,
            'term_id'     => $this->term->id,
            'status'      => Pef::STATUS_PENDING_ADMISSION,
            'total_units' => 12,
        ]);

        $this->workflow->approve($pef, $this->admission, 'Admission OK');
        $this->assertEquals(Pef::STATUS_PENDING_REGISTRAR, $pef->fresh()->status);

        $this->workflow->approve($pef->fresh(), $this->registrar, 'Registrar OK');
        $this->assertEquals(Pef::STATUS_PENDING_CASHIER, $pef->fresh()->status);
    }

    public function test_returning_pef_releases_seats_and_restarts_at_advising_on_resubmit(): void
    {
        $this->section1->update(['enrolled_count' => 1]);

        $pef = Pef::create([
            'student_id'  => $this->student->id,
            'term_id'     => $this->term->id,
            'status'      => Pef::STATUS_PENDING_ADVISING,
            'total_units' => 12,
        ]);

        PefItem::create(['pef_id' => $pef->id, 'section_id' => $this->section1->id]);
        PefItem::create(['pef_id' => $pef->id, 'section_id' => $this->section2->id]);

        $this->workflow->returnToStudent($pef, $this->adviser, 'Please change sections');

        $this->assertEquals(Pef::STATUS_RETURNED, $pef->fresh()->status);
        $this->assertEquals(0, $this->section1->fresh()->enrolled_count);

        // Resubmit restarts at pending_advising
        $this->workflow->submit($pef->fresh(), $this->studentUser, $this->validator);
        $this->assertEquals(Pef::STATUS_PENDING_ADVISING, $pef->fresh()->status);
    }

    public function test_wrong_role_cannot_act_on_pef(): void
    {
        $this->expectException(InvalidTransitionException::class);

        $pef = Pef::create([
            'student_id'  => $this->student->id,
            'term_id'     => $this->term->id,
            'status'      => Pef::STATUS_PENDING_ADVISING,
            'total_units' => 12,
        ]);

        // Cashier tries to act on pending_advising PEF
        $this->workflow->approve($pef, $this->cashier);
    }
}
