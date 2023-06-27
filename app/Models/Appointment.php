<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends MysqlModel
{
    use HasFactory;
    protected $appends = ['endDate', 'endTime', 'endAt', 'statusStr'];
    protected $with = ['therapy'];

    const STATUS_SCHEDULED = 0;
    const STATUS_FINISHED = 1;
    const STATUS_STR = [
        "Scheduled", "Finished"
    ];

    protected static function boot()
    {
        parent::boot();
        self::creating(function($item){
            if(!empty($item->beginDate)) {
                $t = explode(":", $item->beginTime ?? "08:00");
                $t = sprintf("%s:%s:00", $t[0] ?? "08", $t[1] ?? "00");
                $item->beginAt = "$item->beginDate $t";
            }
        });
        self::updating(function($item){
            if(!empty($item->beginDate)) {
                $t = explode(":", $item->beginTime ?? "08:00");
                $t = sprintf("%s:%s:00", $t[0] ?? "08", $t[1] ?? "00");
                $item->beginAt = "$item->beginDate $t";
            }
        });
    }

    public function therapy(): BelongsTo {
        return $this->belongsTo(Therapy::class, 'therapyId')->with(['photos', 'doctor']);
    }

    public function patient(): BelongsTo {
        return $this->belongsTo(Patient::class, 'patientId');
    }

    public function getStatusStrAttribute(): string{
        return self::STATUS_STR[$this->status ?? 0] ?? "";
    }


    public function getEndAtCarbon(): Carbon {
        $durationInMinutes = $this->therapy->maxDuration ?? 30;
        $t = explode(":", $this->beginTime ?? "08:00");
        $t = sprintf("%s:%s:00", $t[0] ?? "08", $t[1] ?? "00");
        return Carbon::createFromFormat($this->dateFormat, "$this->beginDate $t")->addMinutes($durationInMinutes);
    }

    public function getEndAtAttribute(): string {
        return $this->getEndAtCarbon()->toDateTimeString();
    }

    public function getEndDateAttribute(): string {
        return $this->getEndAtCarbon()->toDateString();
    }

    public function getEndTimeAttribute(): string {
        return $this->getEndAtCarbon()->toTimeString();
    }


}
