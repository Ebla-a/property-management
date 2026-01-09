@extends('dashboard.layout')

@section('content')
<div class="p-6 bg-white rounded-lg shadow w-full max-w-xl mx-auto">

    <h1 class="text-xl font-bold mb-6 text-gray-500">
        Change User Role
    </h1>

    <div class="mb-4">
        <p class="text-gray-700"><strong>User:</strong> {{ $user->name }}</p>
        <p class="text-gray-700"><strong>Email:</strong> {{ $user->email }}</p>
    </div>

    <form method="POST" action="{{ route('admin.users.change-role', $user->id) }}">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label class="block text-gray-500 mb-2">Select Role</label>

            <select name="role" class="w-full border rounded px-3 py-2 text-gray-700">
                <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>
                    Admin
                </option>

                <option value="employee" {{ $user->hasRole('employee') ? 'selected' : '' }}>
                    Employee
                </option>

                <option value="customer" {{ $user->hasRole('customer') ? 'selected' : '' }}>
                    Customer
                </option>
            </select>
        </div>

        <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            Update Role
        </button>
    </form>

</div>
@endsection
