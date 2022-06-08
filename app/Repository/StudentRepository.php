<?php

namespace App\Repository;

use App\Models\Grade;
use App\Models\Gender;
use App\Models\My_Parent;
use App\Models\Type_Blood;
use App\Models\Nationalitie;

class StudentRepository implements StudentRepositoryInterface
{
    public function Create_Student(){
        $data['my_classes'] = Grade::all();
        $data['parents'] = My_Parent::all();
        $data['Genders'] = Gender::all();
        $data['nationals'] = Nationalitie::all();
        $data['bloods'] = Type_Blood::all();
        return view('pages.Students.add',$data);
    } 
}
