<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Grade extends Model
{
    use HasTranslations;

    public $translatable = ['Name'];

    protected $fillable = ['Name', 'Notes'];

    protected $table = 'Grades';

    public function Sections(): HasMany
    {
        return $this->hasMany(Section::class, 'Grade_id');
    }
}
