   # Laravel Enrollment System

A web-based school enrollment system built in **Laravel 12**, **MySQL**, and **Laravel Breeze (Blade stack)** with **Tailwind CSS** that accurately mirrors the real-world manual enrollment process:

`Draft` → `Pending Advising` → `Pending Admission` → `Pending Registrar` → `Pending Cashier` → `Officially Enrolled`

Side states: `Returned` (with mandatory remarks, restarting at advising upon resubmission), `Rejected` (permanent rejection), and `Cancelled`.

---

## 🔑 Demo Accounts

All demo accounts use password: **`password`**

| Role | Email | Password | What You Can Do / Purpose |
|---|---|---|---|
| **Student** | `student@school.edu` | `password` | Primary student (Juan dela Cruz, BSIT Yr 1). Has past grades (failed IT201, INC IT203, dropped IT205). Can browse board, pick sections, submit PEF, view remarks, download COR. |
| **Student 2** | `student2@school.edu` | `password` | Clean-record student (Maria Reyes, BSIT Yr 1). Passed all previous courses (IT101–IT104). |
| **Adviser** | `adviser@school.edu` | `password` | Prof. Maria Santos. Advising checkpoint. Views student academic grade history grouped by semester with failed/INC/dropped flagged in red/yellow/gray. Approves, returns with remarks, or rejects. |
| **Admission** | `admission@school.edu` | `password` | Admission Officer. Verifies student credentials. Approves and forwards to Registrar, or returns with remarks. |
| **Registrar** | `registrar@school.edu` | `password` | University Registrar. Validates PEF curriculum requirements. Approves and forwards to Cashier, or returns with remarks. |
| **Cashier** | `cashier@school.edu` | `password` | Cashier Office. Views itemized assessment breakdown (tuition + misc fees), records manual payment with OR number, and issues official enrollment. |
| **Admin** | `admin@school.edu` | `password` | Full CRUD for Departments, Programs, Subjects, Prerequisites, Curriculum mapping, Terms, Classrooms/Rooms, Instructors, Sections (with Schedules), and Users/Roles. |

---

## 🚀 Setup & Installation Instructions

### 1. Requirements
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL / MariaDB (e.g. via XAMPP)

### 2. Database Configuration
Ensure MySQL is running (default port 3306, user `root`, no password). Create database if needed:
```sql
CREATE DATABASE IF NOT EXISTS enrollment_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Check `.env` in `my_app/`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=enrollment_system
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Install Dependencies & Build Frontend
```bash
cd my_app
composer install
npm install
npm run build
```

### 4. Run Migrations & Seeders
This seeds all departments, BSIT program, 12 subjects with prerequisites, rooms, faculty, offered sections with day/time schedules for the active term, and all demo users with realistic grade histories:
```bash
php artisan migrate:fresh --seed
```

### 5. Start Development Server
```bash
php artisan serve
```
Open **`http://localhost:8000`** in your browser.

---

## 📋 The Real-World Enrollment Lifecycle

1. **Board / PEF Builder (`student@school.edu`)**:
   - Student browses the offered schedule on the Board for the active term (`2024-2025 2nd Semester`).
   - Picks sections. A live JavaScript unit counter tracks total units against allowed limits (`12` to `25` units).
   - Submits PEF. `PefValidator` validates:
     - Enrollment period is open
     - Prerequisites passed (via `student_grades`)
     - No schedule day/time conflicts
     - Section capacity is available (`DB::transaction` with `lockForUpdate()`)
     - Unit load min/max constraints
   - Upon successful submission, seats are reserved in `sections.enrolled_count`, audit entry written to `pef_approvals`, and notification sent to the student's adviser.

2. **Advising (`adviser@school.edu`)**:
   - Reviews PEF in Advising queue.
   - Inspects student's semester-by-semester academic history. Flagged subjects (failed IT201, INC IT203) are prominently highlighted.
   - Can **Approve** (forwards to Admission), **Return with Remarks** (releases reserved seats, returns to student with remarks; resubmission restarts at Advising), or **Reject** (permanently releases seats).

3. **Admission Office (`admission@school.edu`)**:
   - Reviews PEF in Admission queue.
   - Validates student requirements. Approves (forwards to Registrar) or returns with remarks.

4. **Registrar (`registrar@school.edu`)**:
   - Reviews PEF in Registrar queue.
   - Confirms official academic validity. Approves (forwards to Cashier) or returns with remarks.

5. **Cashier (`cashier@school.edu`)**:
   - Reviews PEF in Cashier queue.
   - Views itemized assessment computed by `AssessmentService` (`total units × ₱450.00` + Library Fee, Lab Fee, Student Activity Fee, Registration Fee, Athletic Fee).
   - Records manual payment amount and issues an Official Receipt (OR) Number.
   - On submission, PEF status becomes **`enrolled`**.

6. **Officially Enrolled & COR (`student@school.edu`)**:
   - Student sees **Enrolled** status badge.
   - Can view and print the Certificate of Registration (COR).
   - Can download printable PDF Certificate of Registration generated via `barryvdh/laravel-dompdf`.

---

## 🧪 Automated Feature Test Suite

Run PHPUnit tests:
```bash
php artisan test
```

All 35 tests pass:
- **`PefValidatorTest`**:
  - `fails_when_enrollment_period_closed`
  - `fails_when_prerequisite_not_passed`
  - `passes_when_prerequisite_is_passed`
  - `fails_when_schedule_conflicts`
  - `fails_when_section_is_full`
- **`PefWorkflowTest`**:
  - `student_can_submit_valid_pef`
  - `adviser_can_approve_pef`
  - `admission_and_registrar_can_advance_pef`
  - `returning_pef_releases_seats_and_restarts_at_advising_on_resubmit`
  - `wrong_role_cannot_act_on_pef`
- Plus all authentication, profile, and authorization tests.

---

## 📁 Key File Map

```
my_app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Department, Program, Subject, Prerequisite, Curriculum, Term, Room, Instructor, Section, User controllers
│   │   │   ├── Student/            # Dashboard, Board, PEF (create, store, edit, show, submit, cancel, cor, corPdf)
│   │   │   ├── Adviser/            # Queue, Review (with grade history), Approve, Return, Reject
│   │   │   ├── Admission/          # Queue, Review, Approve, Return
│   │   │   ├── Registrar/          # Queue, Review, Approve, Return
│   │   │   └── Cashier/            # Queue, Review (assessment breakdown), StorePayment
│   │   ├── Middleware/
│   │   │   └── EnsureRole.php      # Role-based route guard
│   │   └── Requests/
│   │       ├── StorePefRequest.php
│   │       ├── UpdatePefRequest.php
│   │       ├── ApprovePefRequest.php
│   │       ├── ReturnPefRequest.php
│   │       └── RecordPaymentRequest.php
│   ├── Models/                     # 18 Eloquent models with relationships, casts, and helper methods
│   ├── Policies/
│   │   └── PefPolicy.php           # Enforces stage authorization and student PEF isolation
│   └── Services/
│       ├── PefWorkflow.php         # Owns status transitions, seat reservations/releases, and pef_approvals audit trail
│       ├── PefValidator.php        # Enrollment window, prereqs, schedule conflicts, unit limits, capacity lock
│       ├── AcademicHistoryService.php # Semester grade grouping & deficiency flagging
│       └── AssessmentService.php   # Computes tuition & itemized misc fees
├── config/
│   └── fees.php                    # Tuition per unit and miscellaneous fee schedule
├── database/
│   ├── migrations/                 # 22 database migrations
│   ├── factories/                  # Model factories
│   └── seeders/                    # 10 realistic demo seeders
├── resources/views/                # Clean Blade views with Tailwind CSS and reusable components
└── tests/Feature/                  # Workflow & Validator feature tests
```

---

## 📌 Items Completed / Incomplete

- [x] Phase 1: Laravel Breeze auth, role column on users, EnsureRole middleware, role-based dashboard redirects
- [x] Phase 2: All 22 migrations, 18 models with relationships, 8 factories, 10 seeders with realistic demo data
- [x] Phase 3: Full Admin CRUD for departments, programs, subjects, prerequisites, curriculum, terms, rooms, instructors, sections with schedules, users
- [x] Phase 4: Course Board, Student PEF builder with live unit counter, PefValidator service with lockForUpdate capacity reservation
- [x] Phase 5: PefWorkflow service, adviser review queue with historical grades & deficiencies, admission queue, registrar queue, PefPolicy authorization
- [x] Phase 6: AssessmentService, Cashier payment queue & OR number recording, COR view, DomPDF COR generation
- [x] Phase 7: Database notifications, full feature test suite (35 of 35 tests passing)
- [x] Phase 8: Complete README documentation and seeded demo accounts
- **Remaining / Optional Future Extensions**:
  - Email notification transport (currently uses Laravel's robust database notifications driver; can configure SMTP in `.env` if desired)
  - Student photo upload / avatar
