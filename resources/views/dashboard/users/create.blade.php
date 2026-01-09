{{-- resources/views/dashboard/users/create.blade.php --}}
@extends('dashboard.layout')

@section('content')
<!--
  Create Employee Page
  Displays a form to create a new employee user
-->
<div class="p-6 max-w-2xl mx-auto" dir="ltr">
    <h1 class="text-2xl font-semibold mb-6">Create New Employee</h1>

    {{-- Validation Errors --}}
    @if ($errors->any())
    <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-800 rounded">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('dashboard.admin.employees.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full p-2 border rounded focus:ring focus:ring-indigo-200">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full p-2 border rounded focus:ring focus:ring-indigo-200">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password" name="password" required
                class="w-full p-2 border rounded focus:ring focus:ring-indigo-200">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" required
                class="w-full p-2 border rounded focus:ring focus:ring-indigo-200">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium mb-1">Role</label>
            <select name="role" class="w-full p-2 border rounded">
                <option value="employee" selected>Employee</option>
                <option value="admin">Admin</option>
                <option value="customer">Customer</option>
            </select>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                class="px-5 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                Create
            </button>

            <a href="{{ route('dashboard.admin.employees.index') }}"
                class="text-sm text-gray-600 hover:underline">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection