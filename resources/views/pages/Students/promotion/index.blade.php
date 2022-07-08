@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    {{ trans('main_trans.Students_Promotions') }}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
@section('PageTitle')
    {{ trans('main_trans.Students_Promotions') }}
@stop
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">

    <div class="col-md-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">

                @if (Session::has('error_promotions'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ Session::get('error_promotions') }}</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <h6 style="color: red;font-family: Cairo">{{ trans('Students_trans.Old_academic_grade') }}</h6><br>

                <form method="post" action="{{ route('Promotion.store') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="inputState">{{ trans('Students_trans.Grade') }}</label>
                            <select class="custom-select mr-sm-2" name="grade_id" required>
                                <option selected disabled>{{ trans('Parent_trans.Choose') }}...</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col">
                            <label for="classroom_id">{{ trans('Students_trans.classrooms') }} : <span
                                    class="text-danger">*</span></label>
                            <select class="custom-select mr-sm-2" name="classroom_id" required>

                            </select>
                        </div>

                        <div class="form-group col">
                            <label for="section_id">{{ trans('Students_trans.section') }} : </label>
                            <select class="custom-select mr-sm-2" name="section_id" required>

                            </select>
                        </div>
                    </div>
                    <br>
                    <h6 style="color: red;font-family: Cairo">{{ trans('Students_trans.New_academic_grade') }}</h6>
                    <br>

                    <div class="form-row">
                        <div class="form-group col">
                            <label for="inputState">{{ trans('Students_trans.Grade') }}</label>
                            <select class="custom-select mr-sm-2" name="grade_id_new">
                                <option selected disabled>{{ trans('Parent_trans.Choose') }}...</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col">
                            <label for="Classroom_id">{{ trans('Students_trans.classrooms') }}: <span
                                    class="text-danger">*</span></label>
                            <select class="custom-select mr-sm-2" name="classroom_id_new">

                            </select>
                        </div>
                        <div class="form-group col">
                            <label for="section_id">{{ trans('Students_trans.section') }}: </label>
                            <select class="custom-select mr-sm-2" name="section_id_new">

                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">تاكيد</button>
                </form>

            </div>
        </div>
    </div>

</div>
<!-- row closed -->
@endsection
@section('js')

@toastr_js
@toastr_render


@endsection
