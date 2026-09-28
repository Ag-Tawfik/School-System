@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    {{ trans('Users_trans.Edit_User') }}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
@section('PageTitle')
    {{ trans('Users_trans.Edit_User') }}
@stop
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                <form action="{{ route('Users.update', $user->id) }}" method="post">
                    @method('PUT')
                    @include('pages.Users._form')
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
