<?php

namespace App\Http\Controllers\Teachers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeachers;
use App\Repository\TeacherRepositoryInterface;

class TeacherController extends Controller
{
    protected $Teacher;

    public function __construct(TeacherRepositoryInterface $Teacher)
    {
        $this->Teacher = $Teacher;
    }

    public function index()
    {
        $Teachers = $this->Teacher->getAllTeachers();

        return view('pages.Teachers.Teachers', compact('Teachers'));
    }

    public function create()
    {
        $specializations = $this->Teacher->Getspecialization();

        $genders = $this->Teacher->GetGender();

        return view('pages.Teachers.create', compact('specializations', 'genders'));
    }

    public function store(StoreTeachers $request)
    {
        return $this->Teacher->StoreTeachers($request);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
