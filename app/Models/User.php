<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function advisees()
    {
        return $this->hasMany(Student::class, 'adviser_id');
    }

    public function pefApprovals()
    {
        return $this->hasMany(PefApproval::class, 'acted_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'received_by');
    }

    public function isStudent(): bool    { return $this->role === 'student'; }
    public function isAdviser(): bool    { return $this->role === 'adviser'; }
    public function isAdmission(): bool  { return $this->role === 'admission'; }
    public function isRegistrar(): bool  { return $this->role === 'registrar'; }
    public function isCashier(): bool    { return $this->role === 'cashier'; }
    public function isAdmin(): bool      { return $this->role === 'admin'; }

    public function hasRole(string|array $roles): bool
    {
        return in_array($this->role, (array) $roles, true);
    }
}
