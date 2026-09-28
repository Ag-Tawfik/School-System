<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('User');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
            'role' => ['required', Rule::enum(Role::class)],
            'teacher_id' => ['nullable', 'required_if:role,' . Role::Teacher->value, 'exists:teachers,id'],
            'parent_id' => ['nullable', 'required_if:role,' . Role::Parent->value, 'exists:parents,id'],
        ];
    }

    /**
     * The validated fields ready to save: only the link that matches the role
     * is kept, and a blank password on edit leaves the old one in place.
     */
    public function userAttributes(): array
    {
        $role = Role::from($this->validated('role'));

        $attributes = [
            'name' => $this->validated('name'),
            'email' => $this->validated('email'),
            'role' => $role,
            'teacher_id' => $role === Role::Teacher ? $this->validated('teacher_id') : null,
            'parent_id' => $role === Role::Parent ? $this->validated('parent_id') : null,
        ];

        if ($this->filled('password')) {
            $attributes['password'] = $this->validated('password');
        }

        return $attributes;
    }
}
