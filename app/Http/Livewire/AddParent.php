<?php

namespace App\Http\Livewire;

use App\Models\TheParent;
use App\Models\Nationalitie;
use App\Models\ParentAttachment;
use App\Models\Religion;
use App\Models\BloodType;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddParent extends Component
{
    use WithFileUploads;

    public $successMessage = '';

    public $catchError, $updateMode = false, $photos, $show_table = true, $parent_id;

    public $currentStep = 1,

        // Father_INPUTS
        $email, $password,
        $fatherName, $fatherName_en,
        $fatherNationalID, $fatherPassportID,
        $fatherPhoneNumber, $fatherJobTitle, $fatherJobTitle_en,
        $fatherNationalty_id, $fatherBloodType_id,
        $fatherAddress, $fatherReligion_id,

        // Mother_INPUTS
        $motherName, $motherName_en,
        $motherNationalID, $motherPassportID,
        $motherPhoneNumber, $motherJobTitle, $motherJobTitle_en,
        $motherNationalty_id, $motherBloodType_id,
        $motherAddress, $motherReligion_id;

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'email' => 'required|email',
            'fatherNationalID' => 'required|string|min:10|max:10|regex:/[0-9]{9}/',
            'fatherPassportID' => 'min:10|max:10',
            'fatherPhoneNumber' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'motherNationalID' => 'required|string|min:10|max:10|regex:/[0-9]{9}/',
            'motherPassportID' => 'min:10|max:10',
            'motherPhoneNumber' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ]);
    }

    public function render()
    {
        return view('livewire.add-parent', [
            'nationalities' => Nationalitie::all(),
            'bloodTypes' => BloodType::all(),
            'religions' => Religion::all(),
            'myParents' => TheParent::all(),
        ]);
    }

    public function showformadd()
    {
        $this->show_table = false;
    }

    //firstStepSubmit
    public function firstStepSubmit()
    {
        $this->validate([
            'email' => 'required|unique:parents,email,' . $this->id,
            'password' => 'required',
            'fatherName' => 'required',
            'fatherName_en' => 'required',
            'fatherJobTitle' => 'required',
            'fatherJobTitle_en' => 'required',
            'fatherNationalID' => 'required|unique:parents,fatherNationalID,' . $this->id,
            'fatherPassportID' => 'required|unique:parents,fatherPassportID,' . $this->id,
            'fatherPhoneNumber' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'fatherNationalty_id' => 'required',
            'fatherBloodType_id' => 'required',
            'fatherReligion_id' => 'required',
            'fatherAddress' => 'required',
        ]);

        $this->currentStep = 2;
    }

    //secondStepSubmit
    public function secondStepSubmit()
    {

        $this->validate([
            'motherName' => 'required',
            'motherName_en' => 'required',
            'motherNationalID' => 'required|unique:parents,motherNationalID,' . $this->id,
            'motherPassportID' => 'required|unique:parents,motherPassportID,' . $this->id,
            'motherPhoneNumber' => 'required',
            'motherJobTitle' => 'required',
            'motherJobTitle_en' => 'required',
            'motherNationalty_id' => 'required',
            'motherBloodType_id' => 'required',
            'motherReligion_id' => 'required',
            'motherAddress' => 'required',
        ]);

        $this->currentStep = 3;
    }

    public function submitForm()
    {
        try {
            $parents = new TheParent();
            // Father INPUTS
            $parents->email = $this->email;
            $parents->password = Hash::make($this->password);
            $parents->fatherName = ['en' => $this->fatherName_en, 'ar' => $this->fatherName];
            $parents->fatherNationalID = $this->fatherNationalID;
            $parents->fatherPassportID = $this->fatherPassportID;
            $parents->fatherPhoneNumber = $this->fatherPhoneNumber;
            $parents->fatherJobTitle = ['en' => $this->fatherJobTitle_en, 'ar' => $this->fatherJobTitle];
            $parents->fatherPassportID = $this->fatherPassportID;
            $parents->fatherNationalty_id = $this->fatherNationalty_id;
            $parents->fatherBloodType_id = $this->fatherBloodType_id;
            $parents->fatherReligion_id = $this->fatherReligion_id;
            $parents->fatherAddress = $this->fatherAddress;
            // Mother INPUTS
            $parents->motherName = ['en' => $this->motherName_en, 'ar' => $this->motherName];
            $parents->motherNationalID = $this->motherNationalID;
            $parents->motherPassportID = $this->motherPassportID;
            $parents->motherPhoneNumber = $this->motherPhoneNumber;
            $parents->motherJobTitle = ['en' => $this->motherJobTitle_en, 'ar' => $this->motherJobTitle];
            $parents->motherPassportID = $this->motherPassportID;
            $parents->motherNationalty_id = $this->motherNationalty_id;
            $parents->motherBloodType_id = $this->motherBloodType_id;
            $parents->motherReligion_id = $this->motherReligion_id;
            $parents->motherAddress = $this->motherAddress;
            $parents->save();

            if (!empty($this->photos)) {
                foreach ($this->photos as $photo) {
                    $photo->storeAs($this->fatherNationalID, $photo->getClientOriginalName(), $disk = 'parent_attachments');
                    ParentAttachment::create([
                        'file_name' => $photo->getClientOriginalName(),
                        'parent_id' => TheParent::latest()->first()->id,
                    ]);
                }
            }
            $this->successMessage = trans('messages.success');
            $this->clearForm();
            $this->currentStep = 1;
        } catch (\Exception $e) {
            $this->catchError = $e->getMessage();
        };
    }

    public function edit($id)
    {
        $this->show_table = false;
        $this->updateMode = true;
        $parents = TheParent::where('id', $id)->first();
        $this->Parent_id = $id;
        $this->email = $parents->email;
        $this->password = $parents->password;
        $this->fatherName = $parents->getTranslation('fatherName', 'ar');
        $this->fatherName_en = $parents->getTranslation('fatherName', 'en');
        $this->fatherJobTitle = $parents->getTranslation('fatherJobTitle', 'ar');
        $this->fatherJobTitle_en = $parents->getTranslation('fatherJobTitle', 'en');
        $this->fatherNationalID = $parents->fatherNationalID;
        $this->fatherPassportID = $parents->fatherPassportID;
        $this->fatherPhoneNumber = $parents->fatherPhoneNumber;
        $this->fatherNationalty_id = $parents->fatherNationalty_id;
        $this->fatherBloodType_id = $parents->fatherBloodType_id;
        $this->fatherAddress = $parents->fatherAddress;
        $this->fatherReligion_id = $parents->fatherReligion_id;

        $this->motherName = $parents->getTranslation('motherName', 'ar');
        $this->motherName_en = $parents->getTranslation('fatherName', 'en');
        $this->motherJobTitle = $parents->getTranslation('motherJobTitle', 'ar');
        $this->motherJobTitle_en = $parents->getTranslation('motherJobTitle', 'en');
        $this->motherNationalID = $parents->motherNationalID;
        $this->motherPassportID = $parents->motherPassportID;
        $this->motherPhoneNumber = $parents->motherPhoneNumber;
        $this->motherNationalty_id = $parents->motherNationalty_id;
        $this->motherBloodType_id = $parents->motherBloodType_id;
        $this->motherAddress = $parents->motherAddress;
        $this->motherReligion_id = $parents->motherReligion_id;
    }

    // First Step Submit
    public function firstStepSubmit_edit()
    {
        $this->updateMode = true;
        $this->currentStep = 2;
    }

    // Second Step Submit - Edit
    public function secondStepSubmit_edit()
    {
        $this->updateMode = true;
        $this->currentStep = 3;
    }

    public function submitForm_edit()
    {
        if ($this->Parent_id) {
            $parent = TheParent::find($this->Parent_id);
            $parent->update([
                'fatherPassportID' => $this->fatherPassportID,
                'fatherNationalID' => $this->fatherNationalID,
            ]);
        }
        return redirect()->to('/add_parent');
    }

    public function delete($id)
    {
        TheParent::findOrFail($id)->delete();
        return redirect()->to('/add_parent');
    }

    // Clear Form
    public function clearForm()
    {
        $this->email = '';
        $this->password = '';
        $this->fatherName = '';
        $this->fatherJobTitle = '';
        $this->fatherJobTitle_en = '';
        $this->fatherName_en = '';
        $this->fatherNationalID = '';
        $this->fatherPassportID = '';
        $this->fatherPhoneNumber = '';
        $this->fatherNationalty_id = '';
        $this->fatherBloodType_id = '';
        $this->fatherAddress = '';
        $this->fatherReligion_id = '';

        $this->motherName = '';
        $this->motherJobTitle = '';
        $this->motherJobTitle_en = '';
        $this->motherName_en = '';
        $this->motherNationalID = '';
        $this->motherPassportID = '';
        $this->motherPhoneNumber = '';
        $this->motherNationalty_id = '';
        $this->motherBloodType_id = '';
        $this->motherAddress = '';
        $this->motherReligion_id = '';
    }

    // Back
    public function back($step)
    {
        $this->currentStep = $step;
    }
}
