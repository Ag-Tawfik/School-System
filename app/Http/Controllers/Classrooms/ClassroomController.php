<?php

namespace App\Http\Controllers\Classrooms;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassroom;
use App\Models\Classroom;
use App\Models\Grade;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::all();
        $grades = Grade::all();
        return view('pages.My_Classes.My_Classes', compact('classrooms', 'grades'));
    }

    public function store(StoreClassroom $request)
    {
        $classes_list = $request->classes_list;
        try {
            $request->validated();
            foreach ($classes_list as $class_list) {
                $classrooms = new Classroom();
                $classrooms->name = ['en' => $class_list['name_en'], 'ar' => $class_list['name_ar']];
                $classrooms->grade_id = $class_list['grade_id'];
                $classrooms->save();
            }
            toastr()->success(trans('messages.success'));
            return redirect()->route('Classrooms.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $classroom = Classroom::findOrFail($request->id);
            $classroom->update([
                $classroom->name = ['ar' => $request->name_ar, 'en' => $request->name_en],
                $classroom->grade_id = $request->grade_id,
            ]);
            toastr()->success(trans('messages.Update'));
            return redirect()->route('Classrooms.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        Classroom::findOrFail($request->id)->delete();
        toastr()->error(trans('messages.Delete'));
        return redirect()->route('Classrooms.index');
    }

    public function delete_all(Request $request)
    {
        $delete_all_id = explode(",", $request->delete_all_id);
        Classroom::whereIn('id', $delete_all_id)->Delete();
        toastr()->error(trans('messages.Delete'));
        return redirect()->route('Classrooms.index');
    }

    public function Filter_Classes(Request $request)
    {
        $grades = Grade::all();
        $search = Classroom::select('*')->where('grade_id', '=', $request->grade_id)->get();
        return view('pages.My_Classes.My_Classes', compact('grades'))->withDetails($search);
    }
}
