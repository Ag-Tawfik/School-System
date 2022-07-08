<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\belongsToMany;
use Spatie\Translatable\HasTranslations;

class Section extends Model
{
    use HasTranslations;

    public $translatable = [
        'name',
    ];

    protected $fillable = [
        'name',
        'grade_id',
        'class_id',
    ];

    public function classrooms(): belongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function teachers(): belongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teacher_section');
    }

}
