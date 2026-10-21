<?php

namespace Tests\Feature;

use App\Exceptions\EnrollmentValidationException;
use App\Models\Program;
use App\Models\Section;
use App\Models\SectionSchedule;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use App\Services\PefValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PefValidatorTest extends TestCase
{
    use RefreshDatabase;

    protected Student $student;
    protected Term $term;
    protected PefValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->withRole('student')->create();
        $program = Program::factory()->create();

        $this->student = Student::create([
            'user_id'    => $user->id,
            'student_no' => '2024-VAL1',
            'program_id' => $program->id,
            'year_level' => 1,
            'status'     => 'active',
        ]);

        $this->term = Term::create([
            'school_year'      => '2024-2025',
            'semester'         => '1st',
            'enrollment_start' => now()->subDays(2),
            'enrollment_end'   => now()->addDays(10),
            'is_active'        => true,
        ]);

        $this->validator = new PefValidator();
    }

    public function test_fails_when_enrollment_period_closed(): void
    {
        $this->term->update([
            'enrollment_start' => now()->subDays(20),
            'enrollment_end'   => now()->subDays(5),
        ]);

        $subj = Subject::create(['code' => 'VAL101', 'title' => 'Subject 1', 'units' => 12, 'type' => 'lec']);
        $sec = Section::create([
            'subject_id'     => $subj->id,
            'term_id'        => $this->term->id,
            'section_code'   => 'A',
            'capacity'       => 30,
            'enrolled_count' => 0,
        ]);

        $this->expectException(EnrollmentValidationException::class);
        $this->validator->validate($this->student, $this->term, [$sec->id]);
    }

    public function test_fails_when_prerequisite_not_passed(): void
    {
        $prereq = Subject::create(['code' => 'PRE101', 'title' => 'Intro', 'units' => 3, 'type' => 'lec']);
        $advanced = Subject::create(['code' => 'ADV201', 'title' => 'Advanced', 'units' => 12, 'type' => 'lec']);
        $advanced->prerequisites()->attach($prereq->id);

        $sec = Section::create([
            'subject_id'     => $advanced->id,
            'term_id'        => $this->term->id,
            'section_code'   => 'A',
            'capacity'       => 30,
            'enrolled_count' => 0,
        ]);

        $this->expectException(EnrollmentValidationException::class);
        $this->validator->validate($this->student, $this->term, [$sec->id]);
    }

    public function test_passes_when_prerequisite_is_passed(): void
    {
        $prereq = Subject::create(['code' => 'PRE101', 'title' => 'Intro', 'units' => 3, 'type' => 'lec']);
        $advanced = Subject::create(['code' => 'ADV201', 'title' => 'Advanced', 'units' => 15, 'type' => 'lec']);
        $advanced->prerequisites()->attach($prereq->id);

        StudentGrade::create([
            'student_id' => $this->student->id,
            'subject_id' => $prereq->id,
            'term_id'    => $this->term->id,
            'grade'      => 1.25,
            'remarks'    => 'passed',
        ]);

        $sec = Section::create([
            'subject_id'     => $advanced->id,
            'term_id'        => $this->term->id,
            'section_code'   => 'A',
            'capacity'       => 30,
            'enrolled_count' => 0,
        ]);

        $this->validator->validate($this->student, $this->term, [$sec->id]);
        $this->assertTrue(true);
    }

    public function test_fails_when_schedule_conflicts(): void
    {
        $subj1 = Subject::create(['code' => 'SUBJ1', 'title' => 'Subject 1', 'units' => 6, 'type' => 'lec']);
        $subj2 = Subject::create(['code' => 'SUBJ2', 'title' => 'Subject 2', 'units' => 6, 'type' => 'lec']);

        $sec1 = Section::create(['subject_id' => $subj1->id, 'term_id' => $this->term->id, 'section_code' => 'A', 'capacity' => 30, 'enrolled_count' => 0]);
        $sec2 = Section::create(['subject_id' => $subj2->id, 'term_id' => $this->term->id, 'section_code' => 'B', 'capacity' => 30, 'enrolled_count' => 0]);

        SectionSchedule::create(['section_id' => $sec1->id, 'day' => 'Mon', 'start_time' => '08:00', 'end_time' => '10:00']);
        SectionSchedule::create(['section_id' => $sec2->id, 'day' => 'Mon', 'start_time' => '09:00', 'end_time' => '11:00']);

        $this->expectException(EnrollmentValidationException::class);
        $this->validator->validate($this->student, $this->term, [$sec1->id, $sec2->id]);
    }

    public function test_fails_when_section_is_full(): void
    {
        $subj = Subject::create(['code' => 'FULL101', 'title' => 'Full Class', 'units' => 12, 'type' => 'lec']);
        $sec = Section::create([
            'subject_id'     => $subj->id,
            'term_id'        => $this->term->id,
            'section_code'   => 'A',
            'capacity'       => 20,
            'enrolled_count' => 20, // Full!
        ]);

        $this->expectException(EnrollmentValidationException::class);
        $this->validator->validate($this->student, $this->term, [$sec->id]);
    }
}
