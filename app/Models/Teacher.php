<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\belongsToMany;
use Spatie\Translatable\HasTranslations;

class Teacher extends Model
{
    use HasTranslations;

    public $translatable = [
        'name',
    ];

    protected $guarded = [];

    public function specializations(): belongsTo
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }

    public function genders(): belongsTo
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function sections(): belongsToMany
    {
        return $this->belongsToMany(Section::class, 'teacher_section');
    }
}
