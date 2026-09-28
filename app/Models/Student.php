<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\morphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Student extends Model
{
    use HasTranslations;
    use SoftDeletes;

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

    public function nationality(): belongsTo
    {
        return $this->belongsTo(Nationalitie::class, 'nationalitie_id');
    }

    public function theparent(): belongsTo
    {
        return $this->belongsTo(TheParent::class, 'parent_id');
    }

    /**
     * Limit the query to the students this user may see: every student for
     * an admin, the students in their own sections for a teacher, their own
     * children for a parent, and nothing for an account with no link.
     */
    #[Scope]
    protected function visibleTo(Builder $query, User $user): void
    {
        if ($user->isAdmin()) {
            return;
        }

        if ($user->isTeacher() && $user->teacher_id) {
            $query->whereIn('section_id', function ($sections) use ($user) {
                $sections->select('section_id')->from('teacher_section')->where('teacher_id', $user->teacher_id);
            });

            return;
        }

        if ($user->isParent() && $user->parent_id) {
            $query->where('parent_id', $user->parent_id);

            return;
        }

        $query->whereRaw('1 = 0');
    }
}
