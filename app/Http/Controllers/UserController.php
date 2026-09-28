<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\StoreUserRequest;
use App\Models\Teacher;
use App\Models\TheParent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Accounts are created here by an admin; there is no self-registration.
 */
class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['teacher', 'theparent'])->orderBy('name')->get();

        return view('pages.Users.index', compact('users'));
    }

    public function create()
    {
        return view('pages.Users.create', $this->formData(new User()));
    }

    public function store(StoreUserRequest $request)
    {
        $attributes = $request->userAttributes();
        $attributes['password'] = Hash::make($attributes['password']);

        User::create($attributes);

        toastr()->success(trans('messages.success'));

        return redirect()->route('Users.index');
    }

    public function edit(User $User)
    {
        return view('pages.Users.edit', $this->formData($User));
    }

    public function update(StoreUserRequest $request, User $User)
    {
        $attributes = $request->userAttributes();

        // An admin demoting themselves could leave the school with no admin.
        if ($User->is($request->user()) && $attributes['role'] !== Role::Admin) {
            throw ValidationException::withMessages(['role' => trans('Users_trans.cannot_demote_self')]);
        }

        if (isset($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        $User->update($attributes);

        toastr()->success(trans('messages.Update'));

        return redirect()->route('Users.index');
    }

    public function destroy(User $User)
    {
        if ($User->is(auth()->user())) {
            return redirect()->route('Users.index')->withErrors(['error' => trans('Users_trans.cannot_delete_self')]);
        }

        $User->delete();

        toastr()->error(trans('messages.Delete'));

        return redirect()->route('Users.index');
    }

    private function formData(User $user): array
    {
        return [
            'user' => $user,
            'roles' => Role::cases(),
            'teachers' => Teacher::all(),
            'parents' => TheParent::all(),
        ];
    }
}
