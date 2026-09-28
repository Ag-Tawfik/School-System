@csrf
<div class="form-row">
    <div class="col">
        <label for="name">{{ trans('Users_trans.Name') }}</label>
        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control"
            required>
        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col">
        <label for="email">{{ trans('Users_trans.Email') }}</label>
        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control"
            required>
        @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<br>
<div class="form-row">
    <div class="col">
        <label for="password">{{ trans('Users_trans.Password') }}</label>
        <input type="password" id="password" name="password" class="form-control" autocomplete="new-password"
            @unless ($user->exists) required @endunless>
        @if ($user->exists)
            <small class="text-muted">{{ trans('Users_trans.Password_keep') }}</small>
        @endif
        @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col">
        <label for="role">{{ trans('Users_trans.Role') }}</label>
        <select class="custom-select" id="role" name="role" required>
            @foreach ($roles as $role)
                <option value="{{ $role->value }}" @selected(old('role', $user->role?->value) === $role->value)>
                    {{ $role->label() }}</option>
            @endforeach
        </select>
        @error('role')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<br>
<div class="form-row">
    <div class="col">
        <label for="teacher_id">{{ trans('Users_trans.Teacher') }}</label>
        <select class="custom-select" id="teacher_id" name="teacher_id">
            <option value="">{{ trans('Users_trans.None') }}</option>
            @foreach ($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(old('teacher_id', $user->teacher_id) == $teacher->id)>
                    {{ $teacher->name }}</option>
            @endforeach
        </select>
        <small class="text-muted">{{ trans('Users_trans.Teacher_help') }}</small>
        @error('teacher_id')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col">
        <label for="parent_id">{{ trans('Users_trans.Parent') }}</label>
        <select class="custom-select" id="parent_id" name="parent_id">
            <option value="">{{ trans('Users_trans.None') }}</option>
            @foreach ($parents as $parent)
                <option value="{{ $parent->id }}" @selected(old('parent_id', $user->parent_id) == $parent->id)>
                    {{ $parent->fatherName }} / {{ $parent->motherName }} ({{ $parent->email }})</option>
            @endforeach
        </select>
        <small class="text-muted">{{ trans('Users_trans.Parent_help') }}</small>
        @error('parent_id')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<br>
<button class="btn btn-success btn-sm pull-right" type="submit">{{ trans('Users_trans.Save') }}</button>
