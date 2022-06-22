<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Classroom extends Model
{

    use HasTranslations;

    public $translatable = ['Name_Class'];

    protected $fillable = [
        'Name_Class',
        'Grade_id',
    ];

    public function Grades(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'Grade_id');
    }
}
