{{-- resources/views/dashboard/users/role.blade.php --}}
@extends('dashboard.layout')

@section('content')
<!--
  Change User Role Page
  Allows admin to update a user's role
-->
<div class="p-6 max-w-xl mx-auto" dir="ltr">
    <h1 class="text-2xl font-semibold mb-6">Change User Role</h1>

    <div class="mb-4 bg-white p-4 rounded shadow">
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
    </div>

    @php
    $currentRole = $user->getRoleNames()->first();
    @endphp

    <form method="POST" action="{{ route('dashboard.admin.users.change-role', $user->id) }}">
        @csrf
        @method('PATCH')

        <div class="mb-6">
            <label class="block text-sm font-medium mb-1">Role</label>
            <select name="role" class="w-full p-2 border rounded" required>
                <option value="admin" {{ $currentRole === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="employee" {{ $currentRole === 'employee' ? 'selected' : '' }}>Employee</option>
                <option value="customer" {{ $currentRole === 'customer' ? 'selected' : '' }}>Customer</option>
            </select>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                class="px-5 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                Save Changes
            </button>

            <a href="{{ route('dashboard.admin.employees.index') }}"
                class="text-sm text-gray-600 hover:underline">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection