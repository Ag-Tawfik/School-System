@if ($currentStep != 2)
    <div style="display: none" class="row setup-content" id="step-2">
@endif
<div class="col-xs-12">
    <div class="col-md-12">
        <br>

        <div class="form-row">
            <div class="col">
                <label for="title">{{ trans('Parent_trans.motherName') }}</label>
                <input type="text" wire:model="motherName" class="form-control">
                @error('motherName')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <label for="title">{{ trans('Parent_trans.motherName_en') }}</label>
                <input type="text" wire:model="motherName_en" class="form-control">
                @error('motherName_en')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-3">
                <label for="title">{{ trans('Parent_trans.motherJobTitle') }}</label>
                <input type="text" wire:model="motherJobTitle" class="form-control">
                @error('motherJobTitle')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label for="title">{{ trans('Parent_trans.motherJobTitle_en') }}</label>
                <input type="text" wire:model="motherJobTitle_en" class="form-control">
                @error('motherJobTitle_en')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col">
                <label for="title">{{ trans('Parent_trans.motherNationalID') }}</label>
                <input type="text" wire:model="motherNationalID" class="form-control">
                @error('motherNationalID')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col">
                <label for="title">{{ trans('Parent_trans.motherPassportID') }}</label>
                <input type="text" wire:model="motherPassportID" class="form-control">
                @error('motherPassportID')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col">
                <label for="title">{{ trans('Parent_trans.motherPhoneNumber') }}</label>
                <input type="text" wire:model="motherPhoneNumber" class="form-control">
                @error('motherPhoneNumber')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

        </div>


        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputCity">{{ trans('Parent_trans.fatherNationalty_id') }}</label>
                <select class="custom-select my-1 mr-sm-2" wire:model="motherNationalty_id">
                    <option selected>{{ trans('Parent_trans.Choose') }}...</option>
                    @foreach ($nationalities as $nationalty)
                        <option value="{{ $nationalty->id }}">{{ $nationalty->name }}</option>
                    @endforeach
                </select>
                @error('motherNationalty_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col">
                <label for="inputState">{{ trans('Parent_trans.fatherBloodType_id') }}</label>
                <select class="custom-select my-1 mr-sm-2" wire:model="motherBloodType_id">
                    <option selected>{{ trans('Parent_trans.Choose') }}...</option>
                    @foreach ($bloodTypes as $bloodType)
                        <option value="{{ $bloodType->id }}">{{ $bloodType->name }}</option>
                    @endforeach
                </select>
                @error('motherBloodType_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col">
                <label for="inputZip">{{ trans('Parent_trans.fatherReligion_id') }}</label>
                <select class="custom-select my-1 mr-sm-2" wire:model="motherReligion_id">
                    <option selected>{{ trans('Parent_trans.Choose') }}...</option>
                    @foreach ($religions as $religion)
                        <option value="{{ $religion->id }}">{{ $religion->name }}</option>
                    @endforeach
                </select>
                @error('motherReligion_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="exampleFormControlTextarea1">{{ trans('Parent_trans.motherAddress') }}</label>
            <textarea class="form-control" wire:model="motherAddress" id="exampleFormControlTextarea1" rows="4"></textarea>
            @error('motherAddress')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-danger btn-sm nextBtn btn-lg pull-right" type="button" wire:click="back(1)">
            {{ trans('Parent_trans.Back') }}
        </button>

        @if ($updateMode)
            <button class="btn btn-success btn-sm nextBtn btn-lg pull-right" wire:click="secondStepSubmit_edit"
                type="button">{{ trans('Parent_trans.Next') }}
            </button>
        @else
            <button class="btn btn-success btn-sm nextBtn btn-lg pull-right" type="button"
                wire:click="secondStepSubmit">{{ trans('Parent_trans.Next') }}</button>
        @endif

    </div>
</div>
</div>
