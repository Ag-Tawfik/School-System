<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class My_Parent extends Model
{
    use HasTranslations;

    public $translatable = ['Name_Father', 'Job_Father', 'Name_Mother', 'Job_Mother'];

    protected $table = 'my__parents';

    protected $guarded = [];

    public function ParentAttachments(): HasMany
    {
        return $this->hasMany(ParentAttachment::class);
    }
}
