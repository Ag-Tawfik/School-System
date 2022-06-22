<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TheParent extends Model
{
    use HasTranslations;

    public $translatable = [
        'Name_Father',
        'Job_Father',
        'Name_Mother',
        'Job_Mother',
    ];

    protected $guarded = [];
}
