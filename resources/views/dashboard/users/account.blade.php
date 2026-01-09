{{-- resources/views/dashboard/users/account.blade.php --}}
@extends('dashboard.layout')

@section('content')
<!--
  User Account Status Page
  Allows admin to activate or deactivate a user account
-->
<div class="p-6 max-w-xl mx-auto" dir="ltr">
    <h1 class="text-2xl font-semibold mb-6">User Account Status</h1>

    <div class="mb-4 bg-white p-4 rounded shadow">
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Current Status:</strong>
            {{ $user->is_active ? 'Active' : 'Inactive' }}
        </p>
    </div>

    <form method="POST" action="{{ route('dashboard.admin.users.toggle-status', $user->id) }}">
        @csrf
        @method('PATCH')

        <div class="mb-6">
            <label class="inline-flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1"
                    {{ $user->is_active ? 'checked' : '' }}
                    class="mr-2">
                Activate this account
            </label>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Save
            </button>

            <a href="{{ route('dashboard.admin.employees.index') }}"
                class="text-sm text-gray-600 hover:underline">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection