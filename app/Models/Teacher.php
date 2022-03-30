<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'Name', 'Email', 'Password', 'Specialization_id', 'Gender_id', 'Joining_Date', 'Address'];
}
