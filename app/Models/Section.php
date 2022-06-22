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
        'Name_Section',
    ];

    protected $fillable = [
        'Name_Section',
        'Grade_id',
        'Class_id',
    ];

    public function My_classs(): belongsTo
    {
        return $this->belongsTo(Classroom::class, 'Class_id');
    }

    public function teachers(): belongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teacher_section');
    }

}
