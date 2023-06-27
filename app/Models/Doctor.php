<?php

namespace App\Models;

use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends MysqlModel
{
    use HasFactory;

    const TYPE_DOCTOR = 0;
    const TYPE_NURSE = 1;
    const TYPES_STR = ["Doctor", "Nurse"];

    protected $appends = ['typeStr', 'isDoctor', 'isNurse'];

    protected $casts = [
        'skill_mental' => 'bool',
        'skill_beauty' => 'bool',
        'skill_blood' => 'bool',
    ];

    public function getAvatarUrlAttribute(): string|null
    {
        return $this->assetUrl($this->attributes['avatarUrl']);
    }

    public function getTypeStrAttribute(): string
    {
        return self::TYPES_STR[$this->type ?? 0];
    }

    public function getIsDoctorAttribute(): bool
    {
        return $this->attributes['type'] === self::TYPE_DOCTOR;
    }

    public function getIsNurseAttribute(): bool
    {
        return $this->attributes['type'] === self::TYPE_NURSE;
    }

    public function therapies(): HasMany {
        return $this->hasMany(Therapy::class, 'therapyId');
    }




}
