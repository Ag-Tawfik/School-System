<?php
namespace App\Http\Controllers\Sections;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSections;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Section;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $grades = Grade::with(['Sections'])->get();
        $grades_list = Grade::all();
        $teachers = Teacher::all();
        return view('pages.Sections.Sections', compact('grades', 'grades_list', 'teachers'));
    }

    public function store(StoreSections $request)
    {
        try {
            $request->validated();
            $sections = new Section();
            $sections->name = ['ar' => $request->name_ar, 'en' => $request->name_en];
            $sections->grade_id = $request->grade_id;
            $sections->class_id = $request->class_id;
            $sections->status = 1;
            $sections->save();
            $sections->teachers()->attach($request->teacher_id);
            toastr()->success(trans('messages.success'));
            return redirect()->route('Sections.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(StoreSections $request)
    {
        try {
            $request->validated();
            $sections = Section::findOrFail($request->id);
            $sections->name = ['ar' => $request->name_ar, 'en' => $request->name_en];
            $sections->grade_id = $request->grade_id;
            $sections->class_id = $request->class_id;
            if (isset($request->status)) {
                $sections->status = 1;
            } else {
                $sections->status = 2;
            }
            // update pivot tABLE
            if (isset($request->teacher_id)) {
                $sections->teachers()->sync($request->teacher_id);
            } else {
                $sections->teachers()->sync(array());
            }
            $sections->save();
            toastr()->success(trans('messages.Update'));
            return redirect()->route('Sections.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(request $request)
    {
        Section::findOrFail($request->id)->delete();
        toastr()->error(trans('messages.Delete'));
        return redirect()->route('Sections.index');
    }

    public function getclasses($id)
    {
        $classes_list = Classroom::where("grade_id", $id)->pluck("name", "id");
        return $classes_list;
    }
}
