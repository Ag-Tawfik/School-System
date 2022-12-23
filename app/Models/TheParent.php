<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TheParent extends Model
{
    use HasTranslations;

    public $translatable = [
        'fatherName',
        'fatherJobTitle',
        'motherName',
        'motherJobTitle',
    ];

    public $table = 'parents';

    protected $guarded = [];
}
