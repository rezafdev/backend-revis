<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Therapy extends MysqlModel
{
    use HasFactory;

    const CATEGORIES = [
        'Mental', 'Head', 'Legs', 'Face', 'Standard', 'Fitness', 'Full Body'
    ];

    protected $with = ['photos', 'doctor'];
    protected $withCount = ['photos', 'appointments'];

    public function photos(): HasMany {
        return $this->hasMany(TherapyPhoto::class, 'therapyId');
    }

    public function doctor(): BelongsTo {
        return $this->belongsTo(Doctor::class, 'doctorId');
    }

    public function appointments(): HasMany {
        return $this->hasMany(Appointment::class, 'therapyId');
    }

    public function photosToArray($items): array {
        $arr = array();
        if(!empty($items)) {
            foreach ($items as $item) {
                if(!empty($item['path']))
                    $arr[] = $item['path'];
            }
        }
        return $arr;
    }

    public function toArray(): array
    {
        $arr = parent::toArray();
        $arr['photos'] = $this->photosToArray($arr['photos']);
        return $arr;
    }

}
