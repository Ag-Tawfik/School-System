<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\morphMany;
use Spatie\Translatable\HasTranslations;

class Student extends Model
{
    use HasTranslations;

    public $translatable = [
        'name',
    ];

    protected $guarded = [];

    public function gender(): belongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function grade(): belongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function classroom(): belongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function section(): belongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function images(): morphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function Nationality(): belongsTo
    {
        return $this->belongsTo(Nationalitie::class);
    }

    public function theparent(): belongsTo
    {
        return $this->belongsTo(TheParent::class);
    }
}
