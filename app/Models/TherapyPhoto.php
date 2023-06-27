<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TherapyPhoto extends MysqlModel
{
    use HasFactory;
    public $timestamps = false;

    public function getPathAttribute(): string|null
    {
        return $this->assetUrl($this->attributes['path']);
    }


}
