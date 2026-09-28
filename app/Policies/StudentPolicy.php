<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    public function view(User $user, Student $student): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Soft-deleted (graduated) students drop out of this query, so only
        // admins still reach them.
        return Student::visibleTo($user)->whereKey($student->getKey())->exists();
    }
}
