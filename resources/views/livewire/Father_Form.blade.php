@if ($currentStep != 1)
    <div style="display: none" class="row setup-content" id="step-1">
@endif
<div class="col-xs-12">
    <div class="col-md-12">
        <br>
        <div class="form-row">
            <div class="col">
                <label for="title">{{ trans('Parent_trans.Email') }}</label>
                <input type="email" wire:model="email" class="form-control">
                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <label for="title">{{ trans('Parent_trans.Password') }}</label>
                <input type="password" wire:model="password" class="form-control">
                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <label for="title">{{ trans('Parent_trans.fatherName') }}</label>
                <input type="text" wire:model="fatherName" class="form-control">
                @error('fatherName')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <label for="title">{{ trans('Parent_trans.fatherName_en') }}</label>
                <input type="text" wire:model="fatherName_en" class="form-control">
                @error('fatherName_en')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-3">
                <label for="title">{{ trans('Parent_trans.fatherJobTitle') }}</label>
                <input type="text" wire:model="fatherJobTitle" class="form-control">
                @error('fatherJobTitle')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label for="title">{{ trans('Parent_trans.fatherJobTitle_en') }}</label>
                <input type="text" wire:model="fatherJobTitle_en" class="form-control">
                @error('fatherJobTitle_en')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col">
                <label for="title">{{ trans('Parent_trans.fatherNationalID') }}</label>
                <input type="text" wire:model="fatherNationalID" class="form-control">
                @error('fatherNationalID')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <label for="title">{{ trans('Parent_trans.fatherPassportID') }}</label>
                <input type="text" wire:model="fatherPassportID" class="form-control">
                @error('fatherPassportID')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col">
                <label for="title">{{ trans('Parent_trans.fatherPhoneNumber') }}</label>
                <input type="text" wire:model="fatherPhoneNumber" class="form-control">
                @error('fatherPhoneNumber')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

        </div>


        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputCity">{{ trans('Parent_trans.fatherNationalty_id') }}</label>
                <select class="custom-select my-1 mr-sm-2" wire:model="fatherNationalty_id">
                    <option selected>{{ trans('Parent_trans.Choose') }}...</option>
                    @foreach ($nationalities as $nationalty)
                        <option value="{{ $nationalty->id }}">{{ $nationalty->name }}</option>
                    @endforeach
                </select>
                @error('fatherNationalty_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col">
                <label for="inputState">{{ trans('Parent_trans.fatherBloodType_id') }}</label>
                <select class="custom-select my-1 mr-sm-2" wire:model="fatherBloodType_id">
                    <option selected>{{ trans('Parent_trans.Choose') }}...</option>
                    @foreach ($bloodTypes as $bloodType)
                        <option value="{{ $bloodType->id }}">{{ $bloodType->name }}</option>
                    @endforeach
                </select>
                @error('fatherBloodType_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col">
                <label for="inputZip">{{ trans('Parent_trans.fatherReligion_id') }}</label>
                <select class="custom-select my-1 mr-sm-2" wire:model="fatherReligion_id">
                    <option selected>{{ trans('Parent_trans.Choose') }}...</option>
                    @foreach ($religions as $religion)
                        <option value="{{ $religion->id }}">{{ $religion->name }}</option>
                    @endforeach
                </select>
                @error('fatherReligion_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <div class="form-group">
            <label for="exampleFormControlTextarea1">{{ trans('Parent_trans.fatherAddress') }}</label>
            <textarea class="form-control" wire:model="fatherAddress" id="exampleFormControlTextarea1" rows="4"></textarea>
            @error('fatherAddress')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        @if ($updateMode)
            <button class="btn btn-success btn-sm nextBtn btn-lg pull-right" wire:click="firstStepSubmit_edit"
                type="button">{{ trans('Parent_trans.Next') }}
            </button>
        @else
            <button class="btn btn-success btn-sm nextBtn btn-lg pull-right" wire:click="firstStepSubmit"
                type="button">{{ trans('Parent_trans.Next') }}
            </button>
        @endif

    </div>
</div>
</div>
