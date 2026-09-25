<?php

namespace App\Repository;

use App\Models\BloodType;
use App\Models\Classroom;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Image;
use App\Models\Nationalitie;
use App\Models\Section;
use App\Models\Student;
use App\Models\TheParent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentRepository implements StudentRepositoryInterface
{

    public function Get_Students()
    {
        $students = Student::all();
        return view('pages.Students.index', compact('students'));
    }

    public function Edit_Student($id)
    {
        $data['grades'] = Grade::all();
        $data['parents'] = TheParent::all();
        $data['genders'] = Gender::all();
        $data['nationalities'] = Nationalitie::all();
        $data['bloodtypes'] = BloodType::all();
        $students = Student::findOrFail($id);
        return view('pages.Students.edit', $data, compact('students'));
    }

    public function Update_Student($request)
    {
        try {
            $Edit_Students = Student::findorfail($request->id);
            $Edit_Students->name = ['ar' => $request->name_ar, 'en' => $request->name_en];
            $Edit_Students->email = $request->email;
            $Edit_Students->password = Hash::make($request->password);
            $Edit_Students->gender_id = $request->gender_id;
            $Edit_Students->nationalitie_id = $request->nationalitie_id;
            $Edit_Students->blood_id = $request->blood_id;
            $Edit_Students->birthday = $request->birthday;
            $Edit_Students->grade_id = $request->grade_id;
            $Edit_Students->classroom_id = $request->classroom_id;
            $Edit_Students->section_id = $request->section_id;
            $Edit_Students->parent_id = $request->parent_id;
            $Edit_Students->academic_year = $request->academic_year;
            $Edit_Students->save();
            toastr()->success(trans('messages.Update'));
            return redirect()->route('Students.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function Create_Student()
    {
        $data['grades'] = Grade::all();
        $data['parents'] = TheParent::all();
        $data['genders'] = Gender::all();
        $data['nationalities'] = Nationalitie::all();
        $data['bloodtypes'] = BloodType::all();
        $data['classrooms'] = Classroom::all();
        return view('pages.Students.add', $data);
    }

    public function Get_classrooms($id)
    {
        $list_classes = Classroom::where("grade_id", $id)->pluck("name", "id");
        return $list_classes;
    }

    //Get Sections
    public function Get_Sections($id)
    {
        $list_sections = Section::where("class_id", $id)->pluck("name", "id");
        return $list_sections;
    }

    public function Store_Student($request)
    {
        DB::beginTransaction();

        try {
            $students = new Student();
            $students->name = ['en' => $request->name_en, 'ar' => $request->name_ar];
            $students->email = $request->email;
            $students->password = Hash::make($request->password);
            $students->gender_id = $request->gender_id;
            $students->nationalitie_id = $request->nationalitie_id;
            $students->blood_id = $request->blood_id;
            $students->birthday = $request->birthday;
            $students->grade_id = $request->grade_id;
            $students->classroom_id = $request->classroom_id;
            $students->section_id = $request->section_id;
            $students->parent_id = $request->parent_id;
            $students->academic_year = $request->academic_year;
            $students->save();

            if ($request->hasfile('photos')) {
                $this->storeAttachments($students, $request->file('photos'));
            }
            DB::commit(); // insert data
            toastr()->success(trans('messages.success'));
            return redirect()->route('Students.create');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function Delete_Student($request)
    {
        Student::destroy($request->id);
        toastr()->error(trans('messages.Delete'));
        return redirect()->route('Students.index');
    }

    public function Show_Student($id)
    {
        $Student = Student::findOrFail($id);
        return view('pages.Students.show', compact('Student'));
    }

    public function Upload_attachment($request)
    {
        $student = Student::findOrFail($request->student_id);
        $this->storeAttachments($student, $request->file('photos'));
        toastr()->success(trans('messages.success'));
        return redirect()->route('Students.show', $student->id);
    }

    public function Download_attachment($id)
    {
        $attachment = $this->findStudentAttachment($id);
        abort_unless($attachment->path && Storage::disk('upload_attachments')->exists($attachment->path), 404);
        return Storage::disk('upload_attachments')->download($attachment->path, $attachment->filename);
    }

    public function Delete_attachment($request)
    {
        $attachment = $this->findStudentAttachment($request->id);
        if ($attachment->path) {
            Storage::disk('upload_attachments')->delete($attachment->path);
        }
        $attachment->delete();
        toastr()->error(trans('messages.Delete'));
        return redirect()->route('Students.show', $attachment->imageable_id);
    }

    private function findStudentAttachment($id)
    {
        return Image::where('imageable_type', Student::class)->findOrFail($id);
    }

    // Stored names are generated server-side; the client name is kept only as a display label.
    private function storeAttachments(Student $student, array $files)
    {
        foreach ($files as $file) {
            $path = $file->storeAs(
                'students/' . $student->id,
                Str::uuid() . '.' . $file->guessExtension(),
                'upload_attachments'
            );

            $images = new Image();
            $images->filename = Str::limit(basename($file->getClientOriginalName()), 200, '');
            $images->path = $path;
            $images->imageable_id = $student->id;
            $images->imageable_type = Student::class;
            $images->save();
        }
    }
}
