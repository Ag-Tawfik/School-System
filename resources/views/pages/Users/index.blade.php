@extends('layouts.master')
@section('css')
    @toastr_css
@section('title')
    {{ trans('Users_trans.List_Users') }}
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
@section('PageTitle')
    {{ trans('Users_trans.List_Users') }}
@stop
<!-- breadcrumb -->
@endsection
@section('content')
<!-- row -->
<div class="row">
    <div class="col-md-12 mb-30">
        <div class="card card-statistics h-100">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                <a href="{{ route('Users.create') }}" class="btn btn-success btn-sm" role="button"
                    aria-pressed="true">{{ trans('Users_trans.Add_User') }}</a><br><br>
                <div class="table-responsive">
                    <table id="datatable" class="table table-hover table-sm table-bordered p-0"
                        data-page-length="50" style="text-align: center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ trans('Users_trans.Name') }}</th>
                                <th>{{ trans('Users_trans.Email') }}</th>
                                <th>{{ trans('Users_trans.Role') }}</th>
                                <th>{{ trans('Users_trans.Linked_to') }}</th>
                                <th>{{ trans('Users_trans.Processes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role->label() }}</td>
                                    <td>{{ $user->teacher?->name ?? $user->theparent?->fatherName }}</td>
                                    <td>
                                        <a href="{{ route('Users.edit', $user->id) }}" class="btn btn-info btn-sm"
                                            role="button" aria-pressed="true"><i class="fa fa-edit"></i></a>
                                        @unless ($user->is(auth()->user()))
                                            <form action="{{ route('Users.destroy', $user->id) }}" method="post"
                                                style="display:inline"
                                                onsubmit="return confirm('{{ trans('Users_trans.Delete_confirm') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    title="{{ trans('Users_trans.Delete') }}"><i
                                                        class="fa fa-trash"></i></button>
                                            </form>
                                        @endunless
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
