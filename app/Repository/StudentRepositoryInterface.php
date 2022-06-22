<?php

namespace App\Repository;

interface StudentRepositoryInterface
{
    // Get Students
    public function Get_Students();

    // Show_Student
    public function Show_Student($id);

    // Add Student
    public function Create_Student();

    // Edit Student
    public function Edit_Student($id);

    // Update Student
    public function Update_Student($request);

    // Delete Student
    public function Delete_Student($request);

    // Get classrooms
    public function Get_classrooms($id);

    // Get Sections
    public function Get_Sections($id);

    // Store Student
    public function Store_Student($request);

    public function Upload_attachment($request);

    //Download_attachment
    public function Download_attachment($studentsname, $filename);

    //Delete_attachment
    public function Delete_attachment($request);
}
