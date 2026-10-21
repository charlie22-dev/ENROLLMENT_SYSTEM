<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pef extends Model
{
    use HasFactory;

    const STATUS_DRAFT             = 'draft';
    const STATUS_PENDING_ADVISING  = 'pending_advising';
    const STATUS_PENDING_ADMISSION = 'pending_admission';
    const STATUS_PENDING_REGISTRAR = 'pending_registrar';
    const STATUS_PENDING_CASHIER   = 'pending_cashier';
    const STATUS_ENROLLED          = 'enrolled';
    const STATUS_RETURNED          = 'returned';
    const STATUS_REJECTED          = 'rejected';
    const STATUS_CANCELLED         = 'cancelled';

    const STATUS_FLOW = [
        self::STATUS_PENDING_ADVISING,
        self::STATUS_PENDING_ADMISSION,
        self::STATUS_PENDING_REGISTRAR,
        self::STATUS_PENDING_CASHIER,
        self::STATUS_ENROLLED,
    ];

    const TERMINAL_STATUSES = [
        self::STATUS_ENROLLED,
        self::STATUS_REJECTED,
        self::STATUS_CANCELLED,
    ];

    protected $fillable = [
        'student_id',
        'term_id',
        'status',
        'total_units',
        'remarks',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function items()
    {
        return $this->hasMany(PefItem::class);
    }

    public function approvals()
    {
        return $this->hasMany(PefApproval::class)->orderBy('acted_at');
    }

    public function assessment()
    {
        return $this->hasOne(Assessment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function isEditable(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_RETURNED], true);
    }

    public function isActive(): bool
    {
        return ! in_array($this->status, self::TERMINAL_STATUSES, true);
    }

    public function isPendingAt(string $stage): bool
    {
        return $this->status === 'pending_' . $stage;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT             => 'Draft',
            self::STATUS_PENDING_ADVISING  => 'Pending Advising',
            self::STATUS_PENDING_ADMISSION => 'Pending Admission',
            self::STATUS_PENDING_REGISTRAR => 'Pending Registrar',
            self::STATUS_PENDING_CASHIER   => 'Pending Cashier',
            self::STATUS_ENROLLED          => 'Enrolled',
            self::STATUS_RETURNED          => 'Returned',
            self::STATUS_REJECTED          => 'Rejected',
            self::STATUS_CANCELLED         => 'Cancelled',
            default                        => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            self::STATUS_ENROLLED          => 'green',
            self::STATUS_RETURNED          => 'yellow',
            self::STATUS_REJECTED,
            self::STATUS_CANCELLED         => 'red',
            self::STATUS_DRAFT             => 'gray',
            default                        => 'blue',
        };
    }
}
