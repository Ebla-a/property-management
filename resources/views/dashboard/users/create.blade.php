@extends('dashboard.layout')

@section('content')
<div class="p-6 bg-white rounded-lg shadow w-full max-w-xl mx-auto">

    <h1 class="text-xl font-bold mb-6 text-gray-500">Add New User</h1>

    <form method="POST" action="{{ route('dashboard.users.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-500">Name</label>
            <input type="text" name="name"
                   class="w-full border rounded px-3 py-2 text-gray-700" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-500">Email</label>
            <input type="email" name="email"
                   class="w-full border rounded px-3 py-2 text-gray-700" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-500">Password</label>
            <input type="password" name="password"
                   class="w-full border rounded px-3 py-2 text-gray-700" required>
        </div>

        <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            Save
        </button>
    </form>

</div>
@endsection
