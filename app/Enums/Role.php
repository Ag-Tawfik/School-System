<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Teacher = 'teacher';
    case Parent = 'parent';

    public function label(): string
    {
        return trans('Users_trans.roles.' . $this->value);
    }
}
