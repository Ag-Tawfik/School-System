<?php

namespace App\Repository;

use App\Models\Gender;
use App\Models\Specialization;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

class TeacherRepository implements TeacherRepositoryInterface
{

    public function getAllTeachers()
    {
        return Teacher::all();
    }

    public function Getspecialization()
    {
        return specialization::all();
    }

    public function GetGender()
    {
        return Gender::all();
    }

    public function StoreTeachers($request)
    {
        try {
            $Teachers = new Teacher();
            $Teachers->email = $request->email;
            $Teachers->password = Hash::make($request->password);
            $Teachers->name = ['en' => $request->name_en, 'ar' => $request->name_ar];
            $Teachers->specialization_id = $request->specialization_id;
            $Teachers->gender_id = $request->gender_id;
            $Teachers->joining_date = $request->joining_date;
            $Teachers->address = $request->address;
            $Teachers->save();
            toastr()->success(trans('messages.success'));
            return redirect()->route('Teachers.create');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function editTeachers($id)
    {
        return Teacher::findOrFail($id);
    }

    public function UpdateTeachers($request)
    {
        try {
            $teachers = Teacher::findOrFail($request->id);
            $teachers->email = $request->email;
            $teachers->password = Hash::make($request->password);
            $teachers->name = ['en' => $request->name_en, 'ar' => $request->name_ar];
            $teachers->specialization_id = $request->specialization_id;
            $teachers->gender_id = $request->gender_id;
            $teachers->joining_date = $request->joining_date;
            $teachers->address = $request->address;
            $teachers->save();
            toastr()->success(trans('messages.Update'));
            return redirect()->route('Teachers.index');
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function DeleteTeachers($request)
    {
        Teacher::findOrFail($request->id)->delete();
        toastr()->error(trans('messages.Delete'));
        return redirect()->route('Teachers.index');
    }
}
