<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Patient extends MysqlModel
{
    use HasFactory;
    const SEX_TYPE_MALE = 0;
    const SEX_TYPE_FEMALE = 1;
    const SEX_TYPE_STR = ["Male", "Female"];
    const STATUS_ACTIVE = 0;
    const STATUS_HOLD = 1;
    const STATUS_STR = ["Active", "Hold"];

    protected $appends = ['sexTypeStr', 'fullName', 'isMale', 'isFemale', 'statusStr', 'isActive', 'isHold'];

    protected $withCount = ['appointments', 'openAppointments'];


    public function getSexTypeStrAttribute(): string{
        return self::SEX_TYPE_STR[$this->sexType ?? 0] ?? "";
    }

    public function getStatusStrAttribute(): string{
        return self::STATUS_STR[$this->status ?? 0] ?? "";
    }

    public function getFullNameAttribute(): string {
        return "{$this->attributes['name']} {$this->attributes['surname']}";
    }

    public function getIsMaleAttribute(): bool {
        return $this->sexType === self::SEX_TYPE_MALE;
    }
    public function getIsFemaleAttribute(): bool {
        return $this->sexType === self::SEX_TYPE_FEMALE;
    }
    public function getIsActiveAttribute(): bool {
        return $this->status === self::STATUS_ACTIVE;
    }
    public function getIsHoldAttribute(): bool {
        return $this->status === self::STATUS_HOLD;
    }

    public function appointments(): HasMany {
        return $this->hasMany(Appointment::class, 'patientId')->without('patient');
    }

    public function openAppointments(): HasMany {
        return $this->hasMany(Appointment::class, 'patientId')
            ->without('patient')
            ->where('status', '!=', Appointment::STATUS_FINISHED);
    }

    public function nearestAppointment() {
        $todayDateString = Carbon::today()->toDateString();
        return $this->hasOne(Appointment::class, 'patientId')
            ->without('patient')
            ->orderBy('beginDate')
            ->where('beginDate', '>=', $todayDateString);
    }

}
