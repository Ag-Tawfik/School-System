<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Classroom extends Model
{

    use HasTranslations;

    public $translatable = ['class_name'];

    protected $fillable = [
        'class_name',
        'grade_id',
    ];

    public function Grades(): BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }
}
