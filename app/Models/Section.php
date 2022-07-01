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
        'section_name',
    ];

    protected $fillable = [
        'section_name',
        'grade_id',
        'class_id',
    ];

    public function My_classs(): belongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    public function teachers(): belongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teacher_section');
    }

}
